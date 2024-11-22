@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="flex justify-center items-center h-screen bg-gray-100">
    <div class="w-full max-w-md">
        <form action="{{ route('login') }}" method="POST" class="bg-white shadow-lg rounded-lg px-8 py-10">
            @csrf
            <h2 class="text-3xl font-extrabold text-center text-gray-800 mb-6">Welcome Back!</h2>
            <p class="text-center text-gray-600 mb-4">Log in to access your account.</p>

            <!-- Display validation errors -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-5">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="Enter your email"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500 focus:outline-none">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input type="password" name="password" id="password" required placeholder="Enter your password"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring focus:ring-blue-500 focus:outline-none">
            </div>

            <div class="flex items-center justify-between mb-4">
                <div>
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="remember" class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>
                <a href="#" class="text-sm text-blue-600 hover:underline">Forgot Password?</a>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:ring focus:ring-blue-500">
                Log In
            </button>
        </form>
    </div>
</div>
@endsection
