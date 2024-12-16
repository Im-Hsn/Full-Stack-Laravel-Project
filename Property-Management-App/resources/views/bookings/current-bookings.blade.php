@extends('layouts.app')

@section('title', 'Current Bookings')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gradient-to-r from-blue-200 via-gray-100 to-blue-200 animate-gradient-shift relative overflow-hidden rounded-3xl">
    <!-- Background Icons Layer -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none rounded-3xl">
        <!-- Floating Property Management Icons -->
        <div class="absolute animate-icon-float-1 top-20 left-10 opacity-60">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
        </div>

        <div class="absolute animate-icon-float-2 bottom-20 right-10 opacity-60">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
            </svg>
        </div>

        <div class="absolute animate-icon-float-3 bottom-10 left-1/3 opacity-60">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 text-fuchsia-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
        <div class="absolute animate-icon-float-4 top-20 right-1/4 opacity-60">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-20 h-20 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto bg-white dark:bg-neutral-800 rounded-2xl shadow-2xl">
            <h2 class="text-4xl font-extrabold text-center py-6 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                Current Bookings
            </h2>

            <div class="space-y-4 p-4">
                @forelse ($currentBookings as $booking)
                <div class="group w-full border border-gray-200 dark:border-neutral-600 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 ease-in-out overflow-hidden">
                    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4 items-center relative">
                        <div class="col-span-2">
                            <h3 class="text-2xl font-bold text-gray-800 dark:text-neutral-100 mb-4">
                                {{ $booking->property_title }}
                            </h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-sm text-gray-600 dark:text-neutral-300">
                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>Check-in: {{ $booking->check_in_date }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Check-out: {{ $booking->check_out_date }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Total: ${{ $booking->total_price }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="absolute right-4 top-4 cursor-pointer group-hover:rotate-180 transition-transform duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500 hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>

                        <div class="w-full col-span-3 border-t border-gray-200 dark:border-neutral-600 mt-4 pt-4 hidden group-hover:block transition-all duration-300 ease-in-out">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-gray-50 dark:bg-neutral-700 p-4 rounded-md">
                                    <h4 class="text-lg font-semibold text-indigo-600 dark:text-indigo-300 mb-2">Booking Details</h4>
                                    <div class="space-y-1 text-sm text-gray-700 dark:text-neutral-200">
                                        <p><span class="font-medium">Status:</span> {{ ucfirst($booking->status) }}</p>
                                        <p><span class="font-medium">Created:</span> {{ $booking->created_at }}</p>
                                    </div>
                                </div>
                                <div class="bg-gray-50 dark:bg-neutral-700 p-4 rounded-md">
                                    <h4 class="text-lg font-semibold text-green-600 dark:text-green-300 mb-2">Guest Details</h4>
                                    <div class="space-y-1 text-sm text-gray-700 dark:text-neutral-200">
                                        <p><span class="font-medium">Name:</span> {{ $booking->guest_name }}</p>
                                        <p><span class="font-medium">Email:</span> {{ $booking->guest_email }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-center mt-4">
                                <form action="{{ route('cancel.booking', $booking->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                                        Cancel Booking
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 dark:text-neutral-400 text-center">No current bookings found.</p>
                @endforelse
            </div>
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
        50% { transform: translateY(-30px) rotate(10deg); }
    }

    .animate-icon-float-1 { animation: iconFloat 4s ease-in-out infinite; }
    .animate-icon-float-2 { animation: iconFloat 3.5s ease-in-out infinite 0.5s; }
    .animate-icon-float-3 { animation: iconFloat 4.5s ease-in-out infinite 0.2s; }
    .animate-icon-float-4 { animation: iconFloat 4s ease-in-out infinite 0.7s; }

    /* Gradient Background Animation */
    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .animate-gradient-shift {
        background-size: 200% 200%;
        animation: gradientShift 8s ease infinite;
    } 
    /* Modern Scrollbar */
    * {
        scrollbar-width: thin;
        scrollbar-color: rgba(107, 114, 128, 0.3) transparent;
    }
    *::-webkit-scrollbar {
        width: 6px;
    }
    *::-webkit-scrollbar-track {
        background: transparent;
    }
    *::-webkit-scrollbar-thumb {
        background-color: rgba(107, 114, 128, 0.3);
        border-radius: 20px;
    }
</style>
@endsection