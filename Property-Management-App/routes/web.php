<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

<<<<<<< HEAD
Route::get('/ListProperty',function(){
<<<<<<< HEAD
return view('propertylisting');
=======
    return view('propertylisting');
    });

=======
>>>>>>> parent of 4e5b079 (Fixing the Merge error)
// Test email route
Route::get('/test-email/{status}/{bookingId}', function ($status, $bookingId) {
    Mail::to('host@example.com')->send(new BookingStatusMail($status, $bookingId));
    return "Email sent successfully for Booking #{$bookingId}";
});

// // Booking confirmation route
// Route::get('/booking/confirm/{bookingId}', function ($bookingId) {
//     $booking = Booking::findOrFail($bookingId);
//     if ($booking->status === 'pending') {
//         $booking->status = 'confirmed';
//         $booking->save();
//         return "Booking #{$bookingId} has been confirmed.";
//     }
//     return "Booking #{$bookingId} cannot be confirmed. Current status: {$booking->status}.";
// });

// // Booking cancellation route
// Route::get('/booking/cancel/{bookingId}', function ($bookingId) {
//     $booking = Booking::findOrFail($bookingId);
//     if ($booking->status === 'pending') {
//         $booking->status = 'cancelled';
//         $booking->save();
//         return "Booking #{$bookingId} has been cancelled.";
//     }
//     return "Booking #{$bookingId} cannot be cancelled. Current status: {$booking->status}.";
// });

Route::get('/booking/confirm/{bookingId}', function ($bookingId) {
    $booking = Booking::findOrFail($bookingId);
    $booking->status = 'confirmed';
    $booking->save();

    return "Booking #{$bookingId} has been confirmed.";
});

Route::get('/booking/cancel/{bookingId}', function ($bookingId) {
    $booking = Booking::findOrFail($bookingId);
    $booking->status = 'cancelled';
    $booking->save();

    return "Booking #{$bookingId} has been cancelled.";
>>>>>>> parent of f046139 (Revert "Fixing the Merge error")
});