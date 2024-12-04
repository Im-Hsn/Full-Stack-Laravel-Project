<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Contract\Database;

class ChatController extends Controller
{
    protected $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function index()
    {
        $hostId = Auth::id();
        $firebaseConfig = [
            'apiKey' => env('FIREBASE_API_KEY'),
            'authDomain' => env('FIREBASE_AUTH_DOMAIN'),
            'databaseURL' => env('FIREBASE_DATABASE_URL'),
            'projectId' => env('FIREBASE_PROJECT_ID'),
            'storageBucket' => env('FIREBASE_STORAGE_BUCKET'),
            'messagingSenderId' => env('FIREBASE_MESSAGING_SENDER_ID'),
            'appId' => env('FIREBASE_APP_ID'),
            'measurementId' => env('FIREBASE_MEASUREMENT_ID'),
        ];

        return view('message', [
            'hostId' => $hostId,
            'firebaseConfig' => $firebaseConfig
        ]);
    }

    public function getGuestList($hostId)
    {
        $reference = $this->database->getReference("chats/{$hostId}");
        $snapshot = $reference->getSnapshot();
        
        return response()->json($snapshot->getValue());
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'hostId' => 'required',
            'guestId' => 'required',
            'message' => 'required|string'
        ]);

        $newMessage = [
            'sender' => $request->hostId,
            'text' => $request->message,
            'timestamp' => ['.sv' => 'timestamp'],
            'read' => false
        ];

        $this->database
            ->getReference("chats/{$request->hostId}/{$request->guestId}/messages")
            ->push($newMessage);

        return response()->json(['status' => 'success']);
    }

    public function markAsRead(Request $request)
    {
        $request->validate([
            'hostId' => 'required',
            'guestId' => 'required',
            'messageId' => 'required'
        ]);

        $this->database
            ->getReference("chats/{$request->hostId}/{$request->guestId}/messages/{$request->messageId}")
            ->update(['read' => true]);

        return response()->json(['status' => 'success']);
    }
}