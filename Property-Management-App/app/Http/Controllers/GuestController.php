<?php

namespace App\Http\Controllers;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Http;

class GuestController extends Controller
{
    public function listProperties()
{
    // Fetch properties where is_available is true (1)
    $properties = Property::where('is_available', 1)->get();

    return view('guest.properties', compact('properties'));
}

    public function bookProperty(Request $request)
{
    $guestId = auth()->id(); // Logged-in guest
    $guestName = auth()->user()->name; 
    $propertyId = $request->input('property_id');
    $checkInDate = $request->input('check_in_date');
    $checkOutDate = $request->input('check_out_date');

    // Validate dates
    if (!$checkInDate || !$checkOutDate || $checkInDate > $checkOutDate) {
        return redirect()->back()->withErrors(['error' => 'Invalid check-in or check-out dates.']);
    }

    // Find the property and host
    $property = Property::findOrFail($propertyId);
    $hostId = $property->user_id;

    if ($checkInDate < $property->start_date || $checkOutDate > $property->end_date) {
        return redirect()->back()->withErrors(['error' => 'The requested dates are outside the property\'s availability range.']);
    }
    // Calculate total price
    $checkIn = new \DateTime($checkInDate);
    $checkOut = new \DateTime($checkOutDate);
    $interval = $checkIn->diff($checkOut)->days + 1; // +1 to include the check-in day
    $totalPrice = $interval * $property->price_per_night; // Assume 'price_per_night' exists on Property
    $reservedPropertyId=$this->updatePropertyAvailability($property, $checkInDate, $checkOutDate);
    // Add booking to the bookings table
    Booking::create([
        'user_id' => $guestId,
        'property_id' => $reservedPropertyId,
        'check_in_date' => $checkInDate,
        'check_out_date' => $checkOutDate,
        'total_price' => $totalPrice, // Add total price
        'status' => 'pending',
    ]);

   
    // Initialize Firebase Chat
    $this->initializeFirebaseChat($guestId, $hostId,$guestName);

    return redirect()->route('guest.properties')->with('success', 'Booking request sent to the host and chat initialized!');
}


private function initializeFirebaseChat($guestId, $hostId,$guestName)
{
    $firebaseUrl = "https://messaging-web-2e28d-default-rtdb.firebaseio.com";

    // Define chat path in Firebase
    $chatPath = "{$firebaseUrl}/chats/{$hostId}/{$guestId}.json";

    // Check if the chat already exists
    $existingChatResponse = Http::get($chatPath);

    if ($existingChatResponse->successful() && $existingChatResponse->json() !== null) {
        \Log::info('Chat already exists, skipping initialization.', [
            'url' => $chatPath,
        ]);
        return; // Chat already exists, so we skip reinitialization
    }

    // Chat schema with sanitized keys and casting
    $chatData = [
        'guest_info' => [
        'id' => (string) $guestId,
        'name' => $guestName,
    ],
        'messages' => (object) [ // Cast to object to avoid numeric indexing
            'msg1' => [
                'sender' => (string) $hostId,
                'text' => 'Welcome to your chat with the host!',
                'timestamp' => now()->getPreciseTimestamp(3),
                
            ],
            'msg2' => [
                'sender' => (string) $guestId,
                'text' => 'Hello, I just booked your property!',
                'timestamp' => now()->getPreciseTimestamp(3),
                'read' => false,
            ],
        ],
    ];

    \Log::info('Initializing Firebase chat with corrected data', [
        'url' => "{$firebaseUrl}/chats/{$hostId}/{$guestId}.json",
        'payload' => $chatData,
    ]);

    // Send data to Firebase using REST API
    $response = Http::put("{$firebaseUrl}/chats/{$hostId}/{$guestId}.json", $chatData);

    if ($response->failed()) {
        \Log::error('Failed to initialize Firebase chat', [
            'response_status' => $response->status(),
            'response_body' => $response->body(),
        ]);
        throw new \Exception('Failed to initialize chat in Firebase');
    }
}

/**
 * Update property availability and dates.
 */
private function updatePropertyAvailability($property, $checkInDate, $checkOutDate)
{
    // Parse property start and end dates
    $propertyStart = new \DateTime($property->start_date);
    $propertyEnd = new \DateTime($property->end_date);
    $checkIn = new \DateTime($checkInDate);
    $checkOut = new \DateTime($checkOutDate);

    // Check if the entire range is reserved
    if ($checkIn == $propertyStart && $checkOut == $propertyEnd) {
        $property->is_available = 0; // Entire range reserved
        $property->save();
        return $property->id;
    } 
    // Check if the reservation is at the start of the range
    elseif ($checkIn == $propertyStart) {
        $property->start_date = $checkOut->modify('+1 day')->format('Y-m-d');
        $property->save();
        return $property->id;
    } 
    // Check if the reservation is at the end of the range
    elseif ($checkOut == $propertyEnd) {
        $property->end_date = $checkIn->modify('-1 day')->format('Y-m-d');
        $property->save();
        return $property->id;
    } 
    // Overlapping middle portion: split into three properties
    elseif ($checkIn > $propertyStart && $checkOut < $propertyEnd) {
        // Create a new property record for the second part of the range (after the reservation)
        $newProperty = $property->replicate();
        $newProperty->start_date = $checkOut->modify('+1 day')->format('Y-m-d');
        $newProperty->end_date = $property->end_date;
        $newProperty->save();

        // Create a new property record for the reserved range
        $reservedProperty = $property->replicate();
        $reservedProperty->start_date = $checkInDate;
        $reservedProperty->end_date = $checkOutDate;
        $reservedProperty->is_available = 0; // Mark as reserved (unavailable)
        $reservedProperty->save();

        // Update the current property to reflect the first part of the range (before the reservation)
        $property->end_date = $checkIn->modify('-1 day')->format('Y-m-d');
        $property->save();
        return $reservedProperty->id;
    }

    // Save the changes to the current property
    return null;
}



}
