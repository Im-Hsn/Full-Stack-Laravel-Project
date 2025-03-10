<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'amenities';

    // The attributes that are mass assignable
    protected $fillable = [
        'amenity',
    ];

    // Define the relationship between Amenity and Property through PropertyAmenity
    public function properties() {
        return $this->belongsToMany(Property::class, 'property_amenities');
    }
}
