<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
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
        ->where('bookings.check_in_date', '>', now()) // Upcoming bookings
        ->where('bookings.status', 'pending') // Only pending bookings
        ->get();

    return view('bookings.upcoming-bookings', compact('upcomingBookings'));
}

public function confirmBooking($id)
    {
        $booking = Booking::findOrFail($id);
        if ($booking->status === 'pending') {
            $booking->status = 'confirmed';
            $booking->save();
            return redirect()->route('bookings.upcoming')->with('success', 'Booking confirmed.');
        }
        return redirect()->route('bookings.upcoming')->with('error', 'Booking could not be confirmed.');
    }

    public function cancelUpcomingBooking($id)
    {
        $booking = Booking::findOrFail($id);
        if ($booking->status === 'pending') {
            $booking->status = 'cancelled';
            $booking->save();
            return redirect()->route('bookings.upcoming')->with('success', 'Booking cancelled.');
        }
        return redirect()->route('bookings.upcoming')->with('error', 'Booking could not be cancelled.');
    }

}
