@extends('layouts.app')

@section('title', 'Booking Dashboard')

@section('content')
<div class="grid grid-cols-2 gap-6 h-screen">
    <!-- Current Bookings -->
    <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center justify-center text-center">
        <div class="bg-blue-100 text-blue-600 p-3 rounded-full mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16" />
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">Current Bookings</h3>
        <p class="text-gray-500 mb-4">View and manage your active bookings.</p>
        <a href="{{ route('current.bookings') }}" class="text-blue-600 hover:underline font-medium">Go to Current Bookings</a>
    </div>

    <!-- Past Bookings -->
    <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center justify-center text-center">
        <div class="bg-green-100 text-green-600 p-3 rounded-full mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 14l4-4m0 8l-4-4" />
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">Past Bookings</h3>
        <p class="text-gray-500 mb-4">Access history of completed bookings.</p>
        <a href="{{ route('bookings.past') }}" class="text-green-600 hover:underline font-medium">Go to Past Bookings</a>
    </div>

    <!-- Upcoming Bookings -->
    <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center justify-center text-center">
        <div class="bg-gray-100 text-gray-600 p-3 rounded-full mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 14l-2-2m0 4l2-2" />
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">Upcoming Bookings</h3>
        <p class="text-gray-500 mb-4">Stay informed about future reservations.</p>
        <a href="{{ route('bookings.upcoming') }}" class="text-gray-600 hover:underline font-medium">Go to Upcoming Bookings</a>
    </div>

    <!-- My Properties -->
    <div class="bg-white rounded-lg shadow-md p-6 flex flex-col items-center justify-center text-center">
        <div class="bg-yellow-100 text-yellow-600 p-3 rounded-full mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10l1.392-1.392A2 2 0 015.392 8h13.216a2 2 0 011.414.608L21 10v9a2 2 0 01-2 2H5a2 2 0 01-2-2v-9z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20v-7h10v7" />
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">My Properties</h3>
        <p class="text-gray-500 mb-4">Manage properties listed for booking.</p>
        <a href="#" class="text-yellow-600 hover:underline font-medium">Go to My Properties</a>
    </div>
</div>
@endsection
