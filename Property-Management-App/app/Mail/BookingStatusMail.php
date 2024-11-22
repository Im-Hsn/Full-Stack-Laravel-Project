<?php

// namespace App\Mail;

// use Illuminate\Mail\Mailable;

// class BookingStatusMail extends Mailable
// {
//     public $status;
//     public $bookingId;

//     public function __construct($status, $bookingId)
//     {
//         $this->status = $status;
//         $this->bookingId = $bookingId;
//     }

//     public function build()
//     {
//         return $this->view('booking-status')
//                     ->with([
//                         'status' => $this->status,
//                         'bookingId' => $this->bookingId,
//                     ]);
//     }

// }

namespace App\Mail;

use Illuminate\Mail\Mailable;

class BookingStatusMail extends Mailable
{
    public $status;
    public $bookingId;

    public function __construct($status, $bookingId)
    {
        $this->status = $status;
        $this->bookingId = $bookingId;
    }

    public function build()
    {
        return $this->view('booking-status')
            ->with([
                'status' => $this->status,
                'bookingId' => $this->bookingId,
                'confirmUrl' => url('/booking/confirm/' . $this->bookingId),
                'cancelUrl' => url('/booking/cancel/' . $this->bookingId),
            ])
            ->subject("Action Required: Booking Request #{$this->bookingId}");
    }
}