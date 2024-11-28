<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'reviews';

    // The attributes that are mass assignable
    protected $fillable = [
        'property_id',
        'user_id',
        'rating',
        'comment',
    ];

    // Define the relationship between Review and Property
    public function property() {
        return $this->belongsTo(Property::class);
    }

    // Define the relationship between Review and User
    public function user() {
        return $this->belongsTo(User::class);
    }
}
