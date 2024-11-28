<?php

namespace App\Observers;

use App\Models\Booking;
use App\Models\Property;
use App\Models\Notification;
use App\Events\NotificationCreated;

class BookingObserver
{
    public function created(Booking $booking)
    {
        // Get the host user ID from the associated property
        $property = Property::find($booking->property_id);
        if ($property) {
            $notification= Notification::create([
                'user_id' => $property->user_id,
                'content' => 'A new booking is Pending',
                'read'    => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            event(new NotificationCreated($notification));
        }
        
    }

    public function updated(Booking $booking)
    {
        // Check if the status field is changed
        if ($booking->isDirty('status')) {
            $property = Property::find($booking->property_id);
            if ($property) {
                $notificationContent = '';
                switch ($booking->status) {
                    case 'confirmed':
                        $notificationContent = 'A booking is confirmed';
                        break;
                    case 'cancelled':
                        $notificationContent = 'A booking is cancelled';
                        break;
                }

                if ($notificationContent) {
                    $notification= Notification::create([
                        'user_id' => $property->user_id,
                        'content' => $notificationContent,
                        'read'    => false,
                    ]);
                    event(new NotificationCreated($notification));
                }
            }
        }
        
    }
    
}
