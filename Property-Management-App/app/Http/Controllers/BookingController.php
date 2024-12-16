<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Property;
use Laravel\Pail\ValueObjects\Origin\Console;
class BookingController extends Controller
{
    public function pastBookings()
{
    // Authenticated host ID
    $hostId = auth()->id();

    // Fetch past bookings for properties owned by the host
    $pastBookings = Booking::select('bookings.*', 'properties.title as property_title', 'users.name as guest_name', 'users.id as guest_id')
        ->join('properties', 'bookings.property_id', '=', 'properties.id')
        ->join('users', 'bookings.user_id', '=', 'users.id')
        ->where('properties.user_id', $hostId)
        ->where('bookings.check_out_date', '<', now())
        ->where('bookings.status', 'confirmed') 
        ->get();

    return view('bookings.past', compact('pastBookings'));
}

public function currentBookings()
{
    $hostId = auth()->user()->id;
    
    // Modify the query for current bookings, similar to past bookings
    $currentBookings = Booking::select('bookings.*', 'properties.title as property_title', 'users.name as guest_name', 'users.email as guest_email')
        ->join('properties', 'bookings.property_id', '=', 'properties.id')
        ->join('users', 'bookings.user_id', '=', 'users.id')
        ->where('properties.user_id', $hostId) // Filter by host ID
        ->where('bookings.check_in_date','<',now())
        ->where('bookings.check_out_date', '>', now()) // Current bookings have check-out dates in the future
        ->where('bookings.status', 'confirmed') // Confirmed bookings only
        ->get();

    return view('bookings.current-bookings', compact('currentBookings'));
}

public function cancelBooking($id)
{
    $booking = Booking::find($id);

    if ($booking && $booking->status === 'confirmed') {
        $booking->status = 'cancelled';
        $booking->save();
        return redirect()->route('current.bookings')->with('success', 'Booking successfully cancelled.');
    }

    return redirect()->route('current.bookings')->with('error', 'Booking not found or already cancelled.');
}

public function upcomingBookings()
{
    $hostId = auth()->user()->id; // Assuming host is logged in
    
    // Query for upcoming bookings
    $upcomingBookings = Booking::select('bookings.*', 'properties.title as property_title', 'users.name as guest_name', 'users.email as guest_email')
    ->join('properties', 'bookings.property_id', '=', 'properties.id')
    ->join('users', 'bookings.user_id', '=', 'users.id')
    ->where('properties.user_id', $hostId) // Host's property
    ->where(function ($query) {
        $query->where('bookings.check_in_date', '>', now()) // Future bookings
              ->orWhere(function ($subQuery) {
                  $subQuery->whereDate('bookings.check_in_date', '=', now()->toDateString()) // Today's bookings
                           ->where('bookings.status', 'pending'); // Only pending bookings
              });
    })
    ->get();


    return view('bookings.upcoming-bookings', compact('upcomingBookings'));
}


public function confirmBooking($id)
    {
        $booking = Booking::findOrFail($id);

    // Check if the booking is in a cancellable or pending state
    if (in_array($booking->status, ['cancelled', 'pending'])) {
        $property = Property::find($booking->property_id);

        if ($property) {
            // Apply booking logic only if the status is 'cancelled'
            if ($booking->status === 'cancelled') {
                $this->updatePropertyAvailability($property, $booking->check_in_date, $booking->check_out_date);
            }

            // Update the booking status to confirmed
            $booking->status = 'confirmed';
            $booking->save();

            return redirect()->route('bookings.upcoming')->with('success', 'Booking confirmed and availability updated.');
        }

        return redirect()->route('bookings.upcoming')->with('error', 'Property not found.');
    }

    return redirect()->route('bookings.upcoming')->with('error', 'Booking could not be confirmed.');
    }

    public function cancelUpcomingBooking($id)
    {
        $booking = Booking::findOrFail($id);

        if (in_array($booking->status, ['pending', 'confirmed'])) {
            // Reverse property availability
            $property = Property::find($booking->property_id);
            if ($property) {
                $this->reversePropertyAvailability($property, $booking->check_in_date, $booking->check_out_date);
            }
    
            // Cancel the booking
            $booking->status = 'cancelled';
            $booking->save();
    
            return redirect()->route('bookings.upcoming')->with('success', 'Booking cancelled and availability updated.');
        }
    
        return redirect()->route('bookings.upcoming')->with('error', 'Booking could not be cancelled.');
    }


    private function reversePropertyAvailability($property, $checkInDate, $checkOutDate)
    {
        $propertyStart = new \DateTime($property->start_date);
        $propertyEnd = new \DateTime($property->end_date);
        $checkIn = new \DateTime($checkInDate);
        $checkOut = new \DateTime($checkOutDate);

        $propertyStart->setTimezone(new \DateTimeZone('UTC'));
        $propertyEnd->setTimezone(new \DateTimeZone('UTC'));
        $checkIn->setTimezone(new \DateTimeZone('UTC'));
        $checkOut->setTimezone(new \DateTimeZone('UTC'));
        
        error_log("Entered");
        error_log("CheckOut: " . $checkOut->format('Y-m-d'));
        error_log("PropertyStart - 1 day: " . (clone $propertyStart)->modify('-1 day')->format('Y-m-d'));
        // If the entire range was reserved
        if ($checkIn == $propertyStart && $checkOut == $propertyEnd) {
            error_log("Entered: Reservation range");
            $property->is_available = 1; // Reopen the entire range
            $property->save();
        }
        // If the reservation was at the start of the range
        elseif ($checkOut->format('Y-m-d')  === (clone $propertyStart)->modify('-1 day')->format('Y-m-d')) {
            error_log("Entered: Reservation at the start of the range");
            $property->start_date = $checkIn->format('Y-m-d'); // Adjust the start date
            $property->is_available=1;
            $property->save();
        }
        // If the reservation was at the end of the range
        elseif ($checkIn->format('Y-m-d')  == (clone $propertyEnd)->modify('+1 day')->format('Y-m-d')) {
            error_log("Entered: Reservation at the end of the range");
            $property->end_date = $checkOut->format('Y-m-d'); // Adjust the end date
            $property->is_available=1;
            $property->save();
        }
        // If the reservation was in the middle
        else {
            // Update the reserved range's availability directly
            $reservedProperty = Property::where('start_date', '=', $checkInDate)
    ->where('end_date', '=', $checkOutDate)
    ->where('user_id', auth()->id())
    ->where('id', '=', $property->id) // Match the specific property
    ->where('is_available', '=', 0)
    ->first();

if ($reservedProperty) {
    $reservedProperty->is_available = 1; // Mark as available
    $reservedProperty->save();
}
        }

       
    }
    
    private function updatePropertyAvailability($property, $checkInDate, $checkOutDate)
    {
        $propertyStart = new \DateTime($property->start_date);
        $propertyEnd = new \DateTime($property->end_date);
        $checkIn = new \DateTime($checkInDate);
        $checkOut = new \DateTime($checkOutDate);
    
        if ($checkIn == $propertyStart && $checkOut == $propertyEnd) {
            // Mark the entire range as unavailable
            $property->is_available = 0;
        } elseif ($checkIn->format('Y-m-d') === $propertyStart->format('Y-m-d')) {
            error_log('entered update start');
            $property->start_date = $checkOut->modify('+1 day')->format('Y-m-d');
            $property->is_available = 0;
        } elseif ($checkOut->format('Y-m-d') === $propertyEnd->format('Y-m-d')) {
            error_log('entered update end');
            $property->end_date = $checkIn->modify('-1 day')->format('Y-m-d');
            $property->is_available = 0;
        } else {
            // Locate the reserved range and mark it as unavailable
            $reservedProperty = Property::where('start_date', '=', $checkInDate)
    ->where('end_date', '=', $checkOutDate)
    ->where('user_id',auth()->id())
    ->where('id', '=', $property->id) // Match the specific property
    ->where('is_available', '=', 1)
    ->first();

if ($reservedProperty) {
    $reservedProperty->is_available = 0; // Mark as unavailable
    $reservedProperty->save();
}

        
        }
    
        $property->save();
    }
    

}
