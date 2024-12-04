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
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\BookingController;

Route::get('/', function () {
    return view('welcome');
});
 
Route::get('/ListProperty',function(){
    return view('propertylisting');
    });
 
// Google OAuth routes
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::middleware(['web'])->group(function () {
    Route::get('information', [GoogleController::class, 'showInformationForm'])->name('information')->middleware('auth');
    Route::post('information', [GoogleController::class, 'saveInformation']);
});
 
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
 
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');
 
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

Route::get('/notifications', [NotificationController::class, 'showNotifications'])
    ->name('notifications.index');

Route::post('/notifications/mark-as-read', [NotificationController::class, 'markNotificationAsRead'])->name('notifications.read');
Route::get('/booking-dashboard', function () {
    return view('booking-dashboard');
})->name('booking.dashboard');

Route::get('/notifications/unread', [NotificationController::class, 'fetchUnreadNotifications']);
Route::get('/past-bookings', [BookingController::class, 'pastBookings'])->name('bookings.past');

Route::get('/current-bookings', [BookingController::class, 'currentBookings'])->name('current.bookings');
Route::post('/cancel-booking/{id}', [BookingController::class, 'cancelBooking'])->name('cancel.booking');

// Route for confirming and canceling upcoming bookings
Route::get('/upcoming-bookings', [BookingController::class, 'upcomingBookings'])->name('bookings.upcoming');
Route::post('/booking/{id}/confirm', [BookingController::class, 'confirmBooking'])->name('confirm.booking');
Route::post('/booking/{id}/cancel-upcoming', [BookingController::class, 'cancelUpcomingBooking'])->name('cancel.upcoming.booking');


