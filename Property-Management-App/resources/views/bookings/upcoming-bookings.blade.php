@extends('layouts.app')

@section('title', 'Upcoming Bookings')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gradient-to-r from-blue-100 via-white to-blue-100 relative overflow-hidden">
    <!-- Background Icons Layer -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <!-- Floating Icons -->
        <div class="absolute animate-icon-float-1 top-20 left-10 opacity-60">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
        </div>
        <div class="absolute animate-icon-float-2 bottom-20 right-10 opacity-60">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
            </svg>
        </div>
    </div>

    <!-- Upcoming Bookings Container -->
    <div class="w-full max-w-6xl px-6 py-10 bg-white shadow-2xl rounded-2xl transform transition-all duration-500 relative z-10 border border-gray-100">
        <h2 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-purple-600 mb-6 text-center">Upcoming Bookings</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($upcomingBookings as $booking)
            <div class="bg-gradient-to-r from-blue-50 via-white to-purple-50 shadow-md rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800">{{ $booking->property_title }}</h3>
                <p class="text-sm text-gray-500">Check-in: {{ $booking->check_in_date }}</p>
                <p class="text-sm text-gray-500">Check-out: {{ $booking->check_out_date }}</p>
                <p class="text-sm text-gray-500">Total Price: ${{ $booking->total_price }}</p>

                <div class="mt-4">
                    <button class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 focus:outline-none" onclick="toggleDetails('booking-{{ $booking->id }}')">
                        Booking Details
                    </button>
                    <div id="booking-{{ $booking->id }}" class="hidden mt-2 text-sm text-gray-700">
                        <p>Status: {{ ucfirst($booking->status) }}</p>
                        <p>Created at: {{ $booking->created_at }}</p>
                    </div>

                    <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 focus:outline-none mt-2" onclick="toggleDetails('guest-{{ $booking->id }}')">
                        Guest Details
                    </button>
                    <div id="guest-{{ $booking->id }}" class="hidden mt-2 text-sm text-gray-700">
                        <p>Guest Name: {{ $booking->guest_name }}</p>
                        <p>Guest Email: {{ $booking->guest_email }}</p>
                    </div>

                    <div class="mt-4 flex justify-between">
                        <!-- Confirm Booking Button -->
                        @if($booking->status == 'pending') <!-- Ensure only pending bookings can be confirmed -->
                        <form action="{{ route('confirm.booking', $booking->id) }}" method="POST" class="w-1/2 pr-2">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
                                Confirm
                            </button>
                        </form>
                        @endif

                        <!-- Cancel Booking Button -->
                        <form action="{{ route('cancel.upcoming.booking', $booking->id) }}" method="POST" class="w-1/2 pl-2">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                                Cancel
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center col-span-full">No upcoming bookings found.</p>
            @endforelse
        </div>
    </div>
</div>

<script>
    function toggleDetails(id) {
        const element = document.getElementById(id);
        element.classList.toggle('hidden');
    }
</script>

<style>
    /* Floating Icon Animations */
    @keyframes iconFloat {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-10px) rotate(5deg); }
    }

    .animate-icon-float-1 { animation: iconFloat 4s ease-in-out infinite; }
    .animate-icon-float-2 { animation: iconFloat 3.5s ease-in-out infinite 0.5s; }
</style>
@endsection