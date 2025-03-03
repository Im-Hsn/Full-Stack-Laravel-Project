<?php

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
use App\Http\Controllers\EarningsController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\GuestController;
use Illuminate\Http\Request;

Route::get('/', function () {
    return redirect('/login');
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
    return redirect('/login');
})->name('logout');

// Earnings Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/earnings', [EarningsController::class, 'index'])
        ->name('earnings.dashboard');

        Route::get('/guest/properties', function () {
        if (Auth::user()->role !== 'guest') {
            abort(403, 'Unauthorized access');
        }

        return app(GuestController::class)->listProperties();
    })->name('guest.properties');

    Route::post('/guest/book', function (Request $request) {
        if (Auth::user()->role !== 'guest') {
            abort(403, 'Unauthorized access');
        }

        return app(GuestController::class)->bookProperty($request);
    })->name('guest.book');

    Route::post('/earnings/update-payout-method', [EarningsController::class, 'updatePayoutMethod'])
        ->name('earnings.update-payout-method');
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

Route::get('/message', function () {
    return view('message');
})->name('message');

Route::get('/update/{id}',function($id){
$controller= new PropertyController;
$property= $controller->getpropertybyid($id);
$amenities= $controller->listamenitiesforupdate();
$propertyamenities= $controller->getpropertyaminity($id);
$fileNames = $property->images_path;
$fileArray = explode(',', $fileNames);
$fileArray = array_map('trim', $fileArray);

return view('UpdateProperty',compact('property','amenities','propertyamenities','fileArray'));
});


Route::put('/updating/{id}',[PropertyController::class, 'updateproperty'])->name('update.property');
Route::get('/myproperties/{id}', [PropertyController::class, 'getuserproperties'])->name('myproperties');

Route::delete('/delete/{userid}/{propertyid}',function($userid,$propertyid){
    $controllers = new PropertyController();
    try {
        $controllers->deletepropertybyid($propertyid);
        return redirect("/myproperties/{$userid}")->with('success', 'Property deleted successfully.');
    } catch (\Exception $e) {
        return redirect("/myproperties/{$userid}")->with('error', 'Failed to delete property. Please try again.');
    }
});

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


//tickets
Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
Route::delete('/tickets/{id}', [TicketController::class, 'destroy'])->name('tickets.destroy');
Route::get('/tickets/{id}', [TicketController::class, 'show'])->name('tickets.show');
Route::get('/tickets/resolve/{id}', [TicketController::class, 'resolve'])->name('tickets.resolve');
