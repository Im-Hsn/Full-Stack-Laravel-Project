@extends('layouts.app')

@section('title', 'Ticket Details')

@section('content')
<div class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-10 space-y-6">
                <div class="flex items-center justify-between border-b pb-6 border-gray-200">
                    <h2 class="text-4xl font-extrabold text-gray-900">Ticket Details</h2>
                    <div class="flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                        <span class="text-gray-500 text-sm">Ticket Details</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-xs uppercase font-semibold text-gray-500 mb-2">Ticket ID</p>
                            <p class="text-lg font-bold text-gray-900">{{ $ticket->id }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-xs uppercase font-semibold text-gray-500 mb-2">Status</p>
                            <span class="
                                px-3 py-1 rounded-full text-sm font-medium 
                                @if($ticket->status == 'Open') bg-green-100 text-green-800 
                                @elseif($ticket->status == 'Closed') bg-red-100 text-red-800 
                                @else bg-yellow-100 text-yellow-800 @endif
                            ">
                                {{ $ticket->status }}
                            </span>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg space-y-4">
                        <div>
                            <p class="text-xs uppercase font-semibold text-gray-500 mb-2">Subject</p>
                            <p class="text-lg text-gray-900">{{ $ticket->subject }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase font-semibold text-gray-500 mb-2">Service</p>
                            <p class="text-lg text-gray-900">{{ $ticket->service }}</p>
                        </div>
                        <div>
                            <p class="text-xs uppercase font-semibold text-gray-500 mb-2">Priority</p>
                            <span class="
                                px-3 py-1 rounded-full text-sm font-medium 
                                @if($ticket->priority == 'Low') bg-green-100 text-green-800 
                                @elseif($ticket->priority == 'High') bg-red-100 text-red-800 
                                @else bg-yellow-100 text-yellow-800 @endif
                            ">
                                {{ $ticket->priority }}
                            </span>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-lg">
                        <p class="text-xs uppercase font-semibold text-gray-500 mb-2">Message</p>
                        <p class="text-gray-700 leading-relaxed">{{ $ticket->message }}</p>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg flex justify-between items-center">
                        <div>
                            <p class="text-xs uppercase font-semibold text-gray-500 mb-2">Date</p>
                            <p class="text-lg text-gray-900">{{ $ticket->date }}</p>
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection