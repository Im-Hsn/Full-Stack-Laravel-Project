@extends('layouts.app')

@section('title', 'Complete Your Information')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-50">
    <div class="w-full max-w-lg p-6 bg-white shadow-xl rounded-lg transform transition-all duration-500">
        <h2 class="text-4xl font-bold text-center text-gray-700 mb-8">
            Complete Your Information
        </h2>
        <form action="{{ route('information') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            <!-- Role Selection Section -->
            <div class="flex justify-between space-x-4">
                <!-- Guest Button -->
                <button type="button" id="guestBtn" class="w-1/2 flex flex-col items-center justify-center py-4 text-gray-800 bg-gray-200 hover:bg-gray-300 active:scale-95 transition-all duration-300 rounded-lg shadow-lg focus:ring focus:ring-gray-400" onclick="selectRole('guest')">
                    <!-- Minimalistic User Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-2 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="4"></circle>
                        <path d="M12 14c-4 0-6 2-6 6v1h12v-1c0-4-2-6-6-6z"></path>
                    </svg>
                    <span class="text-lg font-medium text-gray-600">Guest</span>
                </button>
                <!-- Host Button -->
                <button type="button" id="hostBtn" class="w-1/2 flex flex-col items-center justify-center py-4 text-gray-800 bg-gray-200 hover:bg-gray-300 active:scale-95 transition-all duration-300 rounded-lg shadow-lg focus:ring focus:ring-gray-400" onclick="selectRole('host')">
                    <!-- Minimalistic House Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-2 text-gray-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9z"></path>
                        <path d="M9 22V12h6v10"></path>
                    </svg>
                    <span class="text-lg font-medium text-gray-600">Host</span>
                </button>
            </div>

            <!-- Hidden Role Input -->
            <input type="hidden" name="role" id="role" value="">

            <!-- Form Fields Section (Appears after Role is Selected) -->
            <div id="form-fields" class="hidden space-y-6 mt-6">
                <div>
                    <label for="profile_image" class="block text-sm font-medium text-gray-700 mb-2">Profile Image</label>
                    <div class="relative">
                        <!-- Custom File Upload Button -->
                        <button type="button" class="w-full py-3 px-4 bg-blue-100 hover:bg-blue-200 active:scale-95 transition-all duration-300 rounded-lg shadow-lg text-gray-800 flex items-center justify-center">
                            <!-- File Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2L6 16C6 16.55 6.45 17 7 17H15C15.55 17 16 16.55 16 16V8L12 4H7C6.45 4 6 4.45 6 5V2Z"></path>
                            </svg>
                            Choose File
                        </button>
                        <!-- Hidden File Input -->
                        <input type="file" name="profile_image" id="profile_image" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full" onchange="updateFileName('profile_image')">
                    </div>
                </div>
                
                <div>
                    <label for="identity_image" class="block text-sm font-medium text-gray-700 mb-2">Identity Image</label>
                    <div class="relative">
                        <!-- Custom File Upload Button -->
                        <button type="button" class="w-full py-3 px-4 bg-blue-100 hover:bg-blue-200 active:scale-95 transition-all duration-300 rounded-lg shadow-lg text-gray-800 flex items-center justify-center">
                            <!-- File Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 2L6 16C6 16.55 6.45 17 7 17H15C15.55 17 16 16.55 16 16V8L12 4H7C6.45 4 6 4.45 6 5V2Z"></path>
                            </svg>
                            Choose File
                        </button>
                        <!-- Hidden File Input -->
                        <input type="file" name="identity_image" id="identity_image" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full" onchange="updateFileName('identity_image')">
                    </div>
                </div>                
                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="text" name="phone_number" id="phone_number" class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-gray-400 focus:outline-none">
                </div>
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <textarea name="address" id="address" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:ring focus:ring-gray-400 focus:outline-none"></textarea>
                </div>
                <button id="saveBtn" type="submit" class="w-full bg-blue-100 hover:bg-blue-200 active:scale-95 transition-all duration-300 text-gray-800 font-bold py-3 px-4 rounded-lg shadow-lg focus:ring focus:ring-blue-400">
                    Save Information
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function selectRole(role) {
        const roleInput = document.getElementById('role');
        const formFields = document.getElementById('form-fields');
        const saveBtn = document.getElementById('saveBtn');

        // Set the selected role
        roleInput.value = role;

        // Show the form fields once a role is selected
        formFields.classList.remove('hidden');
        formFields.classList.add('animate-fadeIn');
    }

    function updateFileName(inputId) {
        const fileInput = document.getElementById(inputId);
        const fileName = fileInput.files[0] ? fileInput.files[0].name : "No file chosen";
        const button = fileInput.previousElementSibling; // The button element
        button.textContent = `File Selected: ${fileName}`;
    }
</script>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fadeIn {
        animation: fadeIn 0.5s ease-out forwards;
    }
</style>
@endsection
