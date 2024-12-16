<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'properties';

    // The attributes that are mass assignable
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'images_path',
        'location',
        'latitude',
        'longitude',
        'price_per_night',
        'cleaning_fee',
        'security_deposit',
        'cancellation_policy',
        'start_date',
        'end_date',
        'is_available',
    ];

    // Define the relationship between Property and User
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Define the relationship between Property and Amenity through PropertyAmenity
    public function amenities() {
        return $this->belongsToMany(Amenity::class, 'property_amenities');
    }

    // Define the relationship with the Booking model (a property can have many bookings)
    public function bookings() {
        return $this->hasMany(Booking::class);
    }

    // Define the relationship with the Review model (a property can have many reviews)
    public function reviews() {
        return $this->hasMany(Review::class);
    }

    
}
