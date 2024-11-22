<?php

// use Illuminate\Support\Facades\Route;
// use App\Mail\BookingStatusMail;
// use Illuminate\Support\Facades\Mail;
// use app\Models\Booking;
// Route::get('/', function () {
//     return view('welcome');
// });



// Route::get('/test-email/{status}/{bookingId}', function ($status, $bookingId) {
//     $viewPath = resource_path('views/email-test.blade.php');
//     if (!file_exists($viewPath)) {
//         dd("View file not found at: " . $viewPath);
//     }
//     Mail::to('your-email@example.com')->send(new BookingStatusMail($status, $bookingId));
//     return view('email-test', compact('status', 'bookingId'));
// });

use Illuminate\Support\Facades\Route;
use App\Models\Booking;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingStatusMail;
use App\Http\Controllers\PropertyController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/ListProperty',function(){
    return view('propertylisting');
    });


Route::get('/ListProperty',[PropertyController::class, 'listamenities']);

Route::post('/insertproperty',[PropertyController::class, 'insertproperty'])->name('insert.property');
Route::post('/upload-image', [PropertyController::class, 'uploadImage']);
Route::post('/delete-image', [PropertyController::class, 'deleteImage']);


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
});