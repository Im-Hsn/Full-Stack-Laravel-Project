<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Property;
use App\Models\Notification;
use App\Models\Message;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'host') {
            return redirect('/guest/properties');
        }

        $properties = Property::where('user_id', $user->id)->get();
        $notifications = Notification::where('user_id', $user->id)->get();
        $messages = Message::where('receiver_id', $user->id)->get();

        return view('booking-dashboard', compact('properties', 'notifications', 'messages'));
    }
}