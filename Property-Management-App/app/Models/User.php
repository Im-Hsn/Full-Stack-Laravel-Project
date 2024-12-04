<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',          // Full name from Google
        'email',         // Email address from Google
        'google_id',     // Google unique ID
        'role',          // Role: 'guest' or 'host'
        'profile_image', // Profile image URL or path
        'is_verified',   // id verification boolean
        'phone_number',  // User phone number
        'address',       // User address
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token', // Used for session management
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
