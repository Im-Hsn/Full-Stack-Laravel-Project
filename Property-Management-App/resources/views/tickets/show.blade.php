@extends('layouts.app')

@section('title', 'Ticket Details')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-6">
    <div class="bg-white p-10 rounded-xl shadow-lg border border-gray-200">
        <h2 class="text-3xl font-semibold text-gray-800 mb-6">Ticket Details</h2>
        <p><strong>ID:</strong> {{ $ticket->id }}</p>
        <p><strong>Subject:</strong> {{ $ticket->subject }}</p>
        <p><strong>Service:</strong> {{ $ticket->service }}</p>
        <p><strong>Priority:</strong> {{ $ticket->priority }}</p>
        <p><strong>Message:</strong> {{ $ticket->message }}</p>
        <p><strong>Status:</strong> {{ $ticket->status }}</p>
        <p><strong>Date:</strong> {{ $ticket->date }}</p>
    </div>
</div>
@endsection