@extends('layouts.app')

@section('title', 'Support Tickets')

@section('content')
<div class="max-w-7xl mx-auto py-12 px-6">
    <div class="bg-white p-10 rounded-xl shadow-lg border border-gray-200 space-y-8">
        <h2 class="text-3xl font-semibold text-gray-800 mb-6">Support Tickets</h2>

        <!-- Ticket Submission Form -->
        <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                <input type="text" name="subject" id="subject" class="block w-full border-gray-300 rounded-lg shadow-sm" required>
            </div>
            <div>
                <label for="service" class="block text-sm font-medium text-gray-700">Service</label>
                <input type="text" name="service" id="service" class="block w-full border-gray-300 rounded-lg shadow-sm" required>
            </div>
            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                <select name="priority" id="priority" class="block w-full border-gray-300 rounded-lg shadow-sm" required>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
            </div>
            <div>
                <label for="message" class="block text-sm font-medium text-gray-700">Message</label>
                <textarea name="message" id="message" class="block w-full border-gray-300 rounded-lg shadow-sm" required></textarea>
            </div>
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700">Image (Optional)</label>
                <input type="file" name="image" id="image" class="block w-full border-gray-300 rounded-lg shadow-sm">
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Submit Ticket</button>
        </form>

        <!-- Tickets Table -->
        <h3 class="text-2xl font-semibold text-gray-800 mt-10">My Tickets</h3>
        <table class="min-w-full bg-white rounded-lg border-collapse">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-4 py-2">ID</th>
                    <th class="border border-gray-300 px-4 py-2">Subject</th>
                    <th class="border border-gray-300 px-4 py-2">Status</th>
                    <th class="border border-gray-300 px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tickets as $ticket)
                <tr>
                    <td class="border border-gray-300 px-4 py-2">{{ $ticket->id }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $ticket->subject }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $ticket->status }}</td>
                    <td class="border border-gray-300 px-4 py-2 space-x-2">
                        <a href="{{ route('tickets.show', $ticket->id) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg">View</a>
                        <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="border border-gray-300 px-4 py-2 text-center">No tickets found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection