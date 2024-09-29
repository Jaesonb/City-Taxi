<h1>New Trip Created</h1>

<p>Hello {{ $driver->name }},</p>

<p>You have been assigned a new trip. Please review the details below:</p>

<ul>
    <li><strong>Pickup Location:</strong> {{ $trip->pickup_location }}</li>
    <li><strong>Dropoff Location:</strong> {{ $trip->dropoff_location }}</li>
    <li><strong>Pickup Time:</strong> {{ $trip->pickup_time }}</li>
    <li><strong>Fare:</strong> Rs.{{ number_format($trip->fare, 2) }}</li>
</ul>

<p>To accept or decline the trip, please use the following links:</p>

<a href="{{ route('drivers.accept_trip', $trip->id) }}">Accept Trip</a> |
<a href="{{ route('drivers.decline_trip', $trip->id) }}">Decline Trip</a>

<p>Thank you!</p>
