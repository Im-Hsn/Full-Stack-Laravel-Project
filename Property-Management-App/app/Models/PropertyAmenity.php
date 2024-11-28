<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyAmenity extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'property_amenities';
    public $timestamps = false;
    // The attributes that are mass assignable
    protected $fillable = [
        'property_id',
        'amenity_id',
    ];

    // Define the relationship between PropertyAmenity and Property
    public function property() {
        return $this->belongsTo(Property::class);
    }

    // Define the relationship between PropertyAmenity and Amenity
    public function amenity() {
        return $this->belongsTo(Amenity::class);
    }
}
