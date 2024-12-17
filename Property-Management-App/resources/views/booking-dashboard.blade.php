@extends('layouts.app')

@section('title', 'Booking Dashboard')

@section('content')
<div class="container mx-auto mt-6 relative">
    <!-- Dashboard Tour Popup -->
    @if(session('first_login', true))
    <div id="dashboard-tour" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full relative">
            <button 
                onclick="closeTour()" 
                class="absolute top-4 right-4 text-gray-500 hover:text-gray-700"
            >
                &times;
            </button>

            <div id="tour-steps">
                <div class="tour-step" data-step="0">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Welcome to Your Booking Dashboard</h2>
                    <p class="text-gray-600 mb-4">Let's take a quick tour of your dashboard features.</p>
                </div>

                <div class="tour-step hidden" data-step="1">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Current Bookings</h2>
                    <p class="text-gray-600 mb-4">View and manage all your active bookings in one convenient section.</p>
                </div>

                <div class="tour-step hidden" data-step="2">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Past Bookings</h2>
                    <p class="text-gray-600 mb-4">Access the complete history of your completed bookings.</p>
                </div>

                <div class="tour-step hidden" data-step="3">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">Upcoming Bookings</h2>
                    <p class="text-gray-600 mb-4">Stay informed about all your future reservations and planned trips.</p>
                </div>

                <div class="tour-step hidden" data-step="4">
                    <h2 class="text-2xl font-bold text-gray-800 mb-4">My Properties</h2>
                    <p class="text-gray-600 mb-4">Manage and view the properties you have listed for booking.</p>
                </div>
            </div>

            <div class="flex justify-between items-center mt-6">
                <button 
                    id="prev-btn" 
                    onclick="changeTourStep(-1)" 
                    class="text-blue-600 hover:text-blue-800 hidden"
                >
                    Previous
                </button>
                <button 
                    id="next-btn" 
                    onclick="changeTourStep(1)" 
                    class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded"
                >
                    Next
                </button>
            </div>

            <div class="flex justify-center mt-4">
                @for($i = 0; $i < 5; $i++)
                    <span 
                        id="step-indicator-{{ $i }}" 
                        class="h-2 w-2 rounded-full mx-1 {{ $i === 0 ? 'bg-blue-600' : 'bg-gray-300' }}"
                    ></span>
                @endfor
            </div>
        </div>
    </div>
    @endif

    <!-- Rest of the dashboard remains the same as before -->
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
            @php
                $user = Auth::user();
                $userid = $user->id;
            @endphp
            <h3 class="text-xl font-semibold text-gray-700 mb-2">My Properties</h3>
            <p class="text-gray-500 mb-4">Manage properties listed for booking.</p>
            <a href="/myproperties/{{ $userid }}" class="text-yellow-600 hover:underline font-medium">Go to My Properties</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentStep = 0;
    const totalSteps = 5;

    function changeTourStep(direction) {
        const steps = document.querySelectorAll('.tour-step');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');

        // Hide current step
        steps[currentStep].classList.add('hidden');
        document.getElementById(`step-indicator-${currentStep}`).classList.remove('bg-blue-600');
        document.getElementById(`step-indicator-${currentStep}`).classList.add('bg-gray-300');

        // Update step
        currentStep += direction;

        // Handle start/end of tour
        if (currentStep < 0) currentStep = 0;
        if (currentStep >= totalSteps) {
            closeTour();
            return;
        }

        // Show new step
        steps[currentStep].classList.remove('hidden');
        document.getElementById(`step-indicator-${currentStep}`).classList.remove('bg-gray-300');
        document.getElementById(`step-indicator-${currentStep}`).classList.add('bg-blue-600');

        // Update button visibility
        prevBtn.classList.toggle('hidden', currentStep === 0);
        nextBtn.textContent = currentStep === totalSteps - 1 ? 'Finish' : 'Next';
    }

    function closeTour() {
        document.getElementById('dashboard-tour').style.display = 'none';
        // Optional: we can set a session or cookie to prevent future tours
    }
</script>
@endpush
@endsection