<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Events\NewNotificationEvent;
class NotificationController extends Controller
{
    public function showNotifications()
    {
        // Temporarily fetch notifications for user with id=1
        $notifications = Notification::where('user_id', 1)  // Change this to `auth()->id()` later
            ->orderBy('created_at', 'desc')
            ->get();

        // Return the view and pass the notifications data
        return view('notifications.index', compact('notifications'));
    }

    public function markNotificationAsRead(Request $request)
    {
        // Find the notification by its ID
        $notification = Notification::findOrFail($request->notification_id);

        // Mark the notification as read
        $notification->update(['read' => true]);

        // Redirect back to the notifications page
        return back();
    }

    public function fetchUnreadNotifications()
{
    // Fetch unread notifications for host with id=1
    $notifications = Notification::where('user_id', 1) // Change to `auth()->id()` after implementing authentication
        ->where('read', false)
        ->orderBy('created_at', 'desc')
        ->get();

    return response()->json($notifications);
}

}
