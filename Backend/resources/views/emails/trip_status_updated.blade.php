<h1>Your Trip Status Has Been Updated</h1>

<p>Hello {{ $trip->passenger->name }},</p>

<p>Your trip has been {{ strtolower($trip->status) }}.</p>

<ul>
    <li><strong>Pickup Location:</strong> {{ $trip->pickup_location }}</li>
    <li><strong>Dropoff Location:</strong> {{ $trip->dropoff_location }}</li>
    <li><strong>Pickup Time:</strong> {{ $trip->pickup_time }}</li>
    <li><strong>Fare:</strong> Rs.{{ number_format($trip->fare, 2) }}</li>
    <li><strong>Status:</strong> {{ $trip->status }}</li>
</ul>

<p>Thank you for using our service!</p>
