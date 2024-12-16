@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gradient-to-r from-blue-200 via-gray-100 to-blue-200 animate-gradient-shift relative overflow-hidden rounded-3xl">
  
    @if(session('success'))
    <div id="successAlert" class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 max-w-md w-full">
        <div class="bg-white border-l-4 border-emerald-500 p-4 rounded-lg shadow-lg transition-all duration-500 ease-in-out">
            <div class="flex items-center space-x-3">
                <div class="bg-emerald-100 p-2 rounded-full">
                    <svg class="w-6 h-6 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5 4a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-gray-800 text-sm">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @php
        session()->forget('success');
    @endphp
    @endif

    @if(session('error'))
    <div id="errorAlert" class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 max-w-md w-full">
        <div class="bg-white border-l-4 border-red-500 p-4 rounded-lg shadow-lg transition-all duration-500 ease-in-out">
            <div class="flex items-center space-x-3">
                <div class="bg-red-100 p-2 rounded-full">
                    <svg class="w-6 h-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-lienjoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <p class="text-gray-800 text-sm">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @php
        session()->forget('error');
    @endphp
    @endif

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

    <div class="max-w-4xl w-full mx-auto relative z-10 px-4">
        <div class="flex justify-between items-center mb-10">
            <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500 tracking-tight">
                My Properties
            </h1>
        </div>

        <div class="mb-8">
            <form method="GET" action="{{ route('myproperties', ['id' => Auth::id()]) }}" class="flex space-x-4 items-center">
                <div class="relative flex-grow">
                    <input 
                        type="text" 
                        name="search" 
                        placeholder="Search properties..." 
                        value="{{ request('search') }}" 
                        class="w-full border-0 bg-white shadow-md rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-300"
                    />
                </div>

                <div class="relative">
                    <select 
                        name="sort" 
                        class="appearance-none w-full border-0 bg-white shadow-md rounded-lg px-4 py-3 pr-8 focus:ring-2 focus:ring-blue-500 focus:outline-none transition duration-300">
                        <option value="">Sort by Price</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Low to High</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>High to Low</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                        </svg>
                    </div>
                </div>

                <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-lg shadow-lg hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300">
                    Search
                </button>
            </form>
        </div>

        @if($properties->isEmpty())
            <div class="text-center bg-white rounded-lg shadow-lg p-10">
                <p class="text-gray-500 text-lg">No properties found. Try adjusting your search or sort criteria.</p>
            </div>
        @else
            <div class="space-y-6">
                @foreach($properties as $property)
                    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden flex flex-col md:flex-row transition-all duration-300 hover:shadow-xl">
                        <!-- Property Image Section -->
                        <div class="relative w-full md:w-1/3 h-64 md:h-auto overflow-hidden">
                            <div id="carousel-{{ $property->id }}" class="carousel relative flex overflow-x-scroll whitespace-nowrap h-full hide-scrollbar snap-x snap-mandatory">
                                @php
                                    $fileNames = $property->images_path;
                                    $fileArray = explode(',', $fileNames);
                                    $fileArray = array_map('trim', $fileArray);
                                @endphp
                                @foreach($fileArray as $image)
                                    <div class="flex-shrink-0 w-full h-full snap-center snap-always">
                                        <img class="w-full h-full object-cover transition-transform duration-300 hover:scale-105" src="{{ asset('assets/' . $image) }}" alt="{{ $property->title }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Property Information Section -->
                        <div class="p-6 flex-grow flex flex-col space-y-4 w-full md:w-2/3">
                            <!-- Rest of the property information remains unchanged -->
                            <h2 class="text-2xl font-bold text-gray-800 mb-2 hover:text-blue-600 transition duration-300">
                                {{ $property->title }}
                            </h2>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-600">
                                        <span class="font-semibold">Description:</span>
                                        {{ $property->description }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-semibold">Cancellation Policy:</span>
                                        {{ $property->cancellation_policy }}
                                    </p>
                                </div>
                                
                                <div class="space-y-2 text-sm text-gray-700">
                                    <div class="flex justify-between border-b pb-1">
                                        <span class="font-medium">Location</span>
                                        <span>{{ $property->location }}</span>
                                    </div>
                                    <div class="flex justify-between border-b pb-1">
                                        <span class="font-medium">Price per Night</span>
                                        <span>${{ $property->price_per_night }}</span>
                                    </div>
                                    <div class="flex justify-between border-b pb-1">
                                        <span class="font-medium">Availability</span>
                                        <span class="{{ $property->is_available ? 'text-emerald-500' : 'text-red-500' }}">
                                            {{ $property->is_available ? 'Available' : 'Not Available' }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="font-medium">Dates</span>
                                        <span>{{ $property->start_date }} → {{ $property->end_date }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex space-x-4 mt-4">
                                <a href="/update/{{$property->id}}" class="flex-1 text-center px-6 py-3 bg-gradient-to-r from-teal-500 to-blue-600 text-white rounded-lg hover:from-teal-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-300">
                                    Update
                                </a>
                               
                            <!-- Delete Button with Modal Confirmation -->
                            <button type="button" id="deleteButton-{{ $property->id }}"class="flex-1 text-center px-6 py-3 bg-gradient-to-r from-red-400 to-red-600 text-white rounded-lg hover:from-red-600 to-red-700 focus:outline-none focus:ring-2 focus:ring-red-400 transition duration-300">
                                Delete
                            </button>

                            <!-- Modal Structure for Delete Confirmation -->
                            <div id="deleteModal-{{ $property->id }}" class="fixed inset-0 z-50 hidden bg-gray-500 bg-opacity-50 flex justify-center items-center transition-all duration-300 transform scale-95">
                                <div class="bg-white rounded-lg w-full sm:w-96 p-8 space-y-6 shadow-xl transform transition-all duration-300 ease-out scale-100">
                                    <h2 class="text-2xl font-semibold text-gray-800">Are you sure you want to delete this property?</h2>
                                    <p class="text-sm text-gray-600">This action cannot be undone. Please confirm to proceed.</p>
                                    
                                    <div class="flex justify-end space-x-4">
                                        <!-- Cancel Button -->
                                        <button id="cancelDelete-{{ $property->id }}" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-full hover:bg-gray-400 transition-all duration-300 transform hover:scale-105">
                                            Cancel
                                        </button>
                                        <!-- Confirm Delete Button -->
                                        <form id="deleteForm-{{ $property->id }}" method="POST" action="/delete/{{$property->user_id}}/{{$property->id}}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" id="confirmDelete-{{ $property->id }}" class="px-6 py-2 bg-gradient-to-r from-red-500 to-red-700 text-white rounded-full hover:from-red-600 hover:to-red-800 transition-all duration-300 transform hover:scale-105">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const successAlert = document.getElementById('successAlert');
    const errorAlert = document.getElementById('errorAlert');

    // Function to handle showing and hiding an alert
    const showAlert = (alertElement) => {
        if (alertElement) {
            // Show the alert
            alertElement.classList.remove('opacity-0', 'scale-95');
            alertElement.classList.add('opacity-100', 'scale-100');

            // Hide it after 5 seconds
            setTimeout(() => {
                alertElement.classList.remove('opacity-100', 'scale-100');
                alertElement.classList.add('opacity-0', 'scale-95');
                setTimeout(() => {
                    alertElement.style.display = 'none';
                }, 300); // Wait for the transition to finish
            }, 5000); // 5 seconds
        }
    };

    // Show success and error alerts if present
    showAlert(successAlert);
    showAlert(errorAlert);

    @foreach($properties  as $property)
        (function() {
            const deleteButton = document.getElementById('deleteButton-{{ $property->id }}');
            const deleteModal = document.getElementById('deleteModal-{{ $property->id }}');
            const confirmDelete = document.getElementById('confirmDelete-{{ $property->id }}');
            const cancelDelete = document.getElementById('cancelDelete-{{ $property->id }}');
            const deleteForm = document.getElementById('deleteForm-{{ $property->id }}');

            // Show the modal when the delete button is clicked
            deleteButton.addEventListener('click', () => {
                deleteModal.classList.remove('hidden');
            });

            // Hide the modal when the cancel button is clicked
            cancelDelete.addEventListener('click', () => {
                deleteModal.classList.add('hidden');
            });

            // Submit the form to delete when confirmed
            confirmDelete.addEventListener('click', () => {
                deleteForm.submit(); // Submit the form to delete the property
            });
        })(); // Immediately invoke the function to scope the variables properly
    @endforeach

    // Carousel Auto-Scrolling Script
    const carousels = document.querySelectorAll('.carousel');

    carousels.forEach(carousel => {
        let interval;
        const scrollStep = 1; // Pixel step per frame
        const frameRate = 16; // Milliseconds between frames (60 FPS)

        // Clone children for infinite scroll
        const children = [...carousel.children];
        children.forEach(child => carousel.appendChild(child.cloneNode(true)));

        const startScroll = () => {
            interval = setInterval(() => {
                carousel.scrollLeft += scrollStep;

                // Reset to the beginning when reaching the end
                if (carousel.scrollLeft >= carousel.scrollWidth / 2) {
                    carousel.scrollLeft = 0;
                }
            }, frameRate);
        };

        const stopScroll = () => {
            clearInterval(interval);
        };

        // Start scrolling automatically
        startScroll();

        // Pause scrolling on hover
        carousel.addEventListener('mouseenter', stopScroll);
        carousel.addEventListener('mouseleave', startScroll);
    });
});

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
    scrollbar-color: rgba(107, 114, 128, 0.4) transparent;
}

*::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

*::-webkit-scrollbar-track {
    background: rgba(229, 231, 235, 0.3);
    border-radius: 10px;
}

*::-webkit-scrollbar-thumb {
    background: linear-gradient(45deg, rgba(59, 130, 246, 0.6), rgba(37, 99, 235, 0.6));
    border-radius: 10px;
    transition: all 0.3s ease;
}

*::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(45deg, rgba(59, 130, 246, 0.8), rgba(37, 99, 235, 0.8));
    box-shadow: 0 0 10px rgba(59, 130, 246, 0.3);
}

*::-webkit-scrollbar-corner {
    background: transparent;
}

/* Optional: Add subtle arrows/indicators */
*::-webkit-scrollbar-button:start {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 24 24' fill='none' stroke='rgba(59,130,246,0.5)' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M18 15l-6-6-6 6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: center;
    opacity: 0.5;
}

*::-webkit-scrollbar-button:end {
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8' viewBox='0 0 24 24' fill='none' stroke='rgba(59,130,246,0.5)' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: center;
    opacity: 0.5;
}
    
</style>
@endpush

@endsection
