<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'bookings';

    // The attributes that are mass assignable
    protected $fillable = [
        'property_id',
        'user_id',
        'check_in_date',
        'check_out_date',
        'total_price',
        'status',
    ];

    // Define the relationship between Booking and Property
    public function property() {
        return $this->belongsTo(Property::class);
    }

    // Define the relationship between Booking and User
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Define the relationship with the Payment model (a booking can have many payments)
    public function payments() {
        return $this->hasMany(Payment::class);
    }
}
