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
                <a href="{{ route('auth.google') }}" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg focus:ring focus:ring-red-500 text-center block">Continue with Google</a>
            </div>
        </form>
    </div>
</div>
@endsection