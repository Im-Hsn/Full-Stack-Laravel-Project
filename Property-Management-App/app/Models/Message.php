<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'messages';

    // The attributes that are mass assignable
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'content',
    ];

    // Define the relationship between Message and Sender (User)
    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Define the relationship between Message and Receiver (User)
    public function receiver() {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
