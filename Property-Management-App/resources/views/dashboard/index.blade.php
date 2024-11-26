@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="flex">
    <div class="w-3/4">
        <h2 class="text-2xl font-bold mb-4">Your Properties</h2>
        @foreach($properties as $property)
            <div class="bg-white shadow-md rounded-lg mb-4 p-4">
                <h3 class="text-xl font-bold">{{ $property->title }}</h3>
                <p>{{ $property->description }}</p>
                <p>Price per night: ${{ $property->price_per_night }}</p>
                <p>Location: {{ $property->location }}</p>
                <button class="bg-blue-500 text-white px-4 py-2 rounded mt-2">Edit</button>
                <button class="bg-red-500 text-white px-4 py-2 rounded mt-2">Delete</button>
            </div>
        @endforeach
    </div>
    <div class="w-1/4 ml-4">
        <h2 class="text-2xl font-bold mb-4">Notifications</h2>
        @foreach($notifications as $notification)
            <div class="bg-white shadow-md rounded-lg mb-4 p-4">
                <p>{{ $notification->content }}</p>
            </div>
        @endforeach

        <h2 class="text-2xl font-bold mb-4">Messages</h2>
        @foreach($messages as $message)
            <div class="bg-white shadow-md rounded-lg mb-4 p-4">
                <p>{{ $message->content }}</p>
            </div>
        @endforeach
    </div>
</div>
@endsection