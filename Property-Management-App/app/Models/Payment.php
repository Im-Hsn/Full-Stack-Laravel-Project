<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'payments';

    // The attributes that are mass assignable
    protected $fillable = [
        'booking_id',
        'amount',
        'payment_method',
        'status',
    ];

    // Define the relationship between Payment and Booking
    public function booking() {
        return $this->belongsTo(Booking::class);
    }
}
