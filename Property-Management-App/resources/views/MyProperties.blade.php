@extends('layouts.app')

@section('content')
<div class="container mx-auto py-12">
@if(session('success'))
<div id="successAlert" class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 bg-white border-l-4 border-green-500 text-gray-800 p-4 rounded-lg shadow-md flex items-center space-x-4 opacity-0 scale-95 transition-all duration-500 ease-out">
    <!-- Icon -->
    <div class="bg-green-100 text-green-600 p-2 rounded-full">
        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5 4a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
    </div>

    <!-- Message -->
    <div class="flex-1">
        <p class="font-medium text-sm">{{ session('success') }}</p>
    </div>
</div>
    @php
        session()->forget('success');
    @endphp
@endif

@if(session('error'))
<div id="errorAlert" class="fixed top-6 left-1/2 transform -translate-x-1/2 z-50 bg-white border-l-4 border-red-500 text-gray-800 p-4 rounded-lg shadow-md flex items-center space-x-4 opacity-0 scale-95 transition-all duration-500 ease-out">
    <!-- Icon -->
    <div class="bg-red-100 text-red-600 p-2 rounded-full">
        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </div>

    <!-- Message -->
    <div class="flex-1">
        <p class="font-medium text-sm">{{ session('error') }}</p>
    </div>
</div>
    @php
        session()->forget('error');
    @endphp
@endif



    <h1 class="text-5xl font-extrabold text-left text-transparent bg-clip-text bg-black tracking-tight mb-8 leading-normal">
        My Properties
    </h1>

    <div class="grid grid-cols-1 gap-10">
        @foreach($userproperties as $property)
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden flex flex-row w-full">
                <!-- Property Image Section (Left) -->
                <div class="relative w-1/3">
                    <div id="carousel-{{ $property->id }}" class="carousel flex overflow-hidden whitespace-nowrap h-full">
                        @php
                           $fileNames = $property->images_path;
                            $fileArray = explode(',', $fileNames);
                            $fileArray = array_map('trim', $fileArray);
                        @endphp
                        @foreach($fileArray as $image)
                            <div class="flex-shrink-0 w-full h-full">
                                <img class="w-full h-full object-cover" src="{{ asset('assets/' . $image) }}" alt="{{ $property->title }}">
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Property Information Section (Right) -->
                <div class="p-6 flex-grow flex flex-col space-y-4 w-2/3">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">{{ $property->title }}</h2>
                    <div class="text-gray-700 text-sm h-auto">
                        <p>
                            <span class="font-semibold">Description:</span>
                            {{ $property->description }}
                        </p>
                    </div>
                    <div class="text-gray-700 text-sm h-auto">
                        <p>
                            <span class="font-semibold">Cancellation Policy:</span>
                            {{ $property->cancellation_policy }}
                        </p>
                    </div>

                    <!-- Details -->
                    <div class="text-sm text-gray-600 space-y-1">
                        <div class="flex justify-between">
                            <span class="font-semibold">Location:</span>
                            <span>{{ $property->location }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold">Price per Night (All Fees Included):</span>
                            <span>${{ $property->price_per_night }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold">Cleaning Fee:</span>
                            <span>${{ $property->cleaning_fee }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold">Security Deposit:</span>
                            <span>${{ $property->security_deposit }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold">Availability:</span>
                            <span class="{{ $property->is_available ? 'text-green-500' : 'text-red-500' }}">
                                {{ $property->is_available ? 'Available' : 'Not Available' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold">Dates:</span>
                            <span>{{ $property->start_date }} → {{ $property->end_date }}</span>
                        </div>
                    </div>

                    <!-- Update and Delete Buttons -->
                    <div class="flex justify-start space-x-4 mt-4">
                        <a href="/update/{{$property->id}}" class="px-8 py-3 bg-gradient-to-r from-teal-400 to-blue-500 text-white rounded-full hover:from-teal-500 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50 text-sm font-semibold shadow-lg transition-all duration-300 transform hover:scale-105">
                            Update
                        </a>

                        <!-- Delete Button with Modal Confirmation -->
                        <button type="button" id="deleteButton-{{ $property->id }}" class="px-8 py-3 bg-gradient-to-r from-red-400 to-red-600 text-white rounded-full hover:from-red-500 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-opacity-50 text-sm font-semibold shadow-lg transition-all duration-300 transform hover:scale-105">
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

    @foreach($userproperties as $property)
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
@endpush

@endsection
