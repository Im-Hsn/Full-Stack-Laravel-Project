@extends('layouts.guest')

@section('title', 'Available Properties')
@section('head')
<style>
    /* Floating Icon Animations */
    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(5deg); }
    }

    .animate-float {
        animation: float 4s ease-in-out infinite;
        transform-origin: center;
    }

    .bg-pattern {
        background-image: 
            linear-gradient(45deg, rgba(16, 185, 129, 0.05) 25%, transparent 25%), 
            linear-gradient(-45deg, rgba(16, 185, 129, 0.05) 25%, transparent 25%);
        background-size: 30px 30px;
    }

    /* Availability Badge Enhanced */
    .availability-badge {
        background: linear-gradient(135deg, #10b981, #059669);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 700;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .availability-badge::before {
        content: '●';
        color: #10b981;
        margin-right: 0.25rem;
    }
</style>
@endsection

@section('content')
@if ($errors->any())
    <div id="errorAlert" class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 max-w-md w-full">
        <div class="bg-white border-l-4 border-red-500 p-4 rounded-lg shadow-lg transition-all duration-500 ease-in-out">
            <div class="flex items-center space-x-3">
                <div class="bg-red-100 p-2 rounded-full">
                    <svg class="w-6 h-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <div>
                    @foreach ($errors->all() as $error)
                        <p class="text-gray-800 text-sm">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif


<div class="property-page flex justify-center items-center min-h-screen bg-gradient-to-r from-teal-100 via-white to-blue-200 relative overflow-hidden">
    <!-- Advanced Background Layer with Enhanced Animated Elements -->
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

    <!-- Available Properties Container with Enhanced Design -->
    <div class="w-full max-w-5xl px-8 py-10 bg-white/90 backdrop-blur-lg shadow-2xl rounded-2xl relative z-10 transform transition-all duration-300 hover:shadow-3xl">
        <h2 class="text-4xl font-extrabold text-black text-center mb-10 tracking-wide uppercase">
            Available Properties
        </h2>

        <!-- Properties Container with Advanced Layout -->
        <div class="grid md:grid-cols-1 gap-8">
            @foreach($properties as $property)
            <div class="bg-white border border-gray-100 shadow-lg rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 group">
                <div class="p-6">
                    <!-- Property Details with Modern Typography -->
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-2xl font-extrabold text-black">
                            {{ $property->title }}
                        </h3>
                        <span  class="text-sm text-green-500 font-bold uppercase tracking-wide bg-gradient-to-r from-teal-100 to-teal-200 text-teal-800 px-3 py-1 rounded-full shadow-sm">
                            Available
                        </span>
                    </div>

                    <p class="text-gray-800 mb-4 leading-relaxed font-medium text-lg">
                        {{ $property->description }}
                    </p>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-700 flex items-center font-semibold tracking-wide">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-black">Location:</span> 
                                <span class="ml-1 text-gray-800">{{ $property->location }}</span>
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-700 flex items-center font-semibold tracking-wide">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-black">Price:</span> 
                                <span class="ml-1 text-gray-800">${{ number_format($property->price_per_night, 2) }}/night</span>
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center mb-4">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-500" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
    </svg>
    <p class="text-sm text-gray-700 flex items-center font-semibold tracking-wide">
        <span class="text-black">Availability:</span> 
        <span class="ml-1 availability-badge">{{ $property->start_date }} to {{ $property->end_date }}</span>
    </p>
</div>

                    <!-- Booking Form with Enhanced Design -->
                    <form method="POST" action="{{ route('guest.book') }}" class="mt-6 space-y-4">
                        @csrf
                        <input type="hidden" name="property_id" value="{{ $property->id }}">

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="check_in_date" class="block text-sm font-bold mb-2 text-black uppercase tracking-wide">Check-in Date</label>
                                <input type="date" name="check_in_date" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition duration-200 font-medium" 
                                    required>
                            </div>

                            <div>
                                <label for="check_out_date" class="block text-sm font-bold mb-2 text-black uppercase tracking-wide">Check-out Date</label>
                                <input type="date" name="check_out_date" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition duration-200 font-medium" 
                                    required>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 via-teal-600 to-cyan-500 text-black font-bold py-3 rounded-lg shadow-lg hover:shadow-xl transform transition-all duration-300 hover:-translate-y-1 hover:scale-[1.02] tracking-wider uppercase">
                            Book Now
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
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
</style>

@endsection