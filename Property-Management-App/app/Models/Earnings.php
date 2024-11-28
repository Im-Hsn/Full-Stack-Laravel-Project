<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Earnings extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'earnings';

    // The attributes that are mass assignable
    protected $fillable = [
        'user_id',
        'amount',
    ];

    // Define the relationship between Earnings and User
    public function user() {
        return $this->belongsTo(User::class);
    }
}
