<!-- <!DOCTYPE html>
<html>
<head>
    <title>Booking Status</title>
</head>
<body>
    <h1>Your booking has been {{ $status }}</h1>
    <p>Booking ID: {{ $bookingId }}</p>
</body>
</html> -->

<!DOCTYPE html>
<html>
<head>
    <title>Action Required: Booking Request</title>
</head>
<body>
    <h1>Booking Request #{{ $bookingId }}</h1>
    <p>A new booking request has been made. Please confirm or cancel this booking.</p>

    <p>
    <a href="{{ url('/booking/confirm/' . $bookingId) }}" 
       style="display: inline-block; padding: 10px 20px; color: #fff; background-color: #28a745; text-decoration: none;">
       Confirm Booking
    </a>
</p>

<p>
    <a href="{{ url('/booking/cancel/' . $bookingId) }}" 
       style="display: inline-block; padding: 10px 20px; color: #fff; background-color: #dc3545; text-decoration: none;">
       Cancel Booking
    </a>
</p>
</body>
</html>
