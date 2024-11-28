@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-semibold text-gray-800 mb-4">Notifications</h1>

    <!-- Loop through the notifications -->
    <div class="space-y-4">
        @foreach ($notifications as $notification)
            <div id="notification-{{ $notification->id }}" 
                class="bg-white p-4 rounded-lg shadow-md 
                {{ $notification->read ? 'bg-gray-100' : 'bg-blue-100 font-bold' }} 
                transition-colors duration-300 hover:bg-blue-50 cursor-pointer"
                onclick="markNotificationAsRead({{ $notification->id }})">

                <p class="text-gray-800">{{ $notification->content }}</p>
                <small class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</small>
            </div>
        @endforeach
    </div>
</div>

<!-- Hidden form for marking notifications as read -->
<form id="markNotificationAsRead" method="POST" action="{{ route('notifications.read') }}" class="hidden">
    @csrf
    <input type="hidden" name="notification_id" id="notificationId">
</form>

@endsection

@push('scripts')
<script>
    // Function to mark notification as read by submitting the hidden form
    function markNotificationAsRead(notificationId) {
        console.log('Notification ID:', notificationId);  // Check if this gets logged

        // Set the notification ID in the hidden form input
        document.getElementById('notificationId').value = notificationId;

        // Submit the form to mark the notification as read
        document.getElementById('markNotificationAsRead').submit();
    }
</script>
@endpush
