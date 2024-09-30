<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trip Summary</title>

    <!-- Bootstrap for styling -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f4f6f9;
        }
        .rating {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .rating input {
            display: none;
        }
        .rating label {
            font-size: 2rem;
            color: #ddd;
            cursor: pointer;
        }
        .rating input:checked ~ label {
            color: gold;
        }
        .rating label:hover,
        .rating label:hover ~ label {
            color: gold;
        }
        .rated-stars {
            font-size: 2rem;
            color: gold;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .star {
            color: #ddd;
        }
        .gold {
            color: gold;
        }
        .trip-card {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .trip-card h5 {
            color: #343a40;
        }
        .trip-card p {
            margin: 5px 0;
            color: #555;
        }
        .trip-summary-header {
            color: #007bff;
        }
        .payment-status {
            font-weight: bold;
            color: #28a745;
        }
        .payment-status.pending {
            color: #dc3545;
        }
    </style>
</head>
<body>
<!-- Navbar with Logout, Profile, and other navigation links -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Trip Details</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profile.edit') }}">Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('transport.post-trip') }}">My Trips</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="trip-summary-header">Trip Summary</h2>

    <!-- Trip details -->
    <div class="card trip-card">
        <div class="card-header">
            <h5>Trip Details</h5>
        </div>
        <div class="card-body">
            <p><strong>Pickup Location:</strong> {{ $trip->pickup_location }}</p>
            <p><strong>Dropoff Location:</strong> {{ $trip->dropoff_location }}</p>
            <p><strong>Pickup Time:</strong> {{ $trip->pickup_time }}</p>
            <p><strong>Distance:</strong> {{ number_format($trip->calculateDistance(), 2) }} km</p>
            <p><strong>Total Fare:</strong> Rs. {{ number_format($trip->calculateFare(), 2) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($trip->status) }}</p>
        </div>
    </div>

    <!-- Driver details -->
    <div class="card trip-card">
        <div class="card-header">
            <h5>Driver Details</h5>
        </div>
        <div class="card-body">
            <p><strong>Name:</strong> {{ $trip->driver->name }}</p>
            <p><strong>Vehicle:</strong> {{ $trip->driver->brand }} {{ $trip->driver->model }} ({{ $trip->driver->vehicle_number }})</p>
            <p><strong>Phone:</strong> {{ $trip->driver->phone_number }}</p>
        </div>
    </div>

    <!-- Payment details -->
    @if($trip->payment)
    <div class="card trip-card">
        <div class="card-header">
            <h5>Payment Details</h5>
        </div>
        <div class="card-body">
            <p><strong>Amount Paid:</strong> Rs. {{ number_format($trip->payment->amount, 2) }}</p>
            <p><strong>Payment Method:</strong> {{ ucfirst($trip->payment->payment_method) }}</p>
            <p><strong>Payment Status:</strong>
                <span class="payment-status {{ $trip->payment->payment_status == 'pending' ? 'pending' : '' }}">
                    {{ ucfirst($trip->payment->payment_status) }}
                </span>
            </p>
        </div>
    </div>
    @else
    <div class="card trip-card">
        <div class="card-header">
            <h5>Payment Details</h5>
        </div>
        <div class="card-body">
            <p><strong>Payment Status:</strong> No payment information available.</p>
        </div>
    </div>
    @endif

    <!-- Rating Display or Form -->
    <div class="card trip-card">
        <div class="mt-4">
            <div class="card-header">
                <h4>My Rating</h4>
            </div>

            @if($trip->rating)
                <!-- Show the rating if it exists -->
                <div class="rated-stars">
                    @for ($i = 1; $i <= 5; $i++)
                        @if($i <= $trip->rating->rating)
                            <span class="gold">&#9733;</span>
                        @else
                            <span class="star">&#9733;</span>
                        @endif
                    @endfor
                </div>

                <!-- Show user's comment if available -->
                @if($trip->rating->comment)
                    <div class="mt-3">
                        <p><strong>Comment:</strong> {{ $trip->rating->comment }}</p>
                    </div>
                @endif

            @else
                <!-- Show the rating form if no rating yet -->
                <form action="{{ route('trip.rate', ['tripId' => $trip->id]) }}" method="POST">
                    @csrf
                    <div class="rating">
                        <input type="radio" name="rating" id="star5" value="5"><label for="star5">&#9733;</label>
                        <input type="radio" name="rating" id="star4" value="4"><label for="star4">&#9733;</label>
                        <input type="radio" name="rating" id="star3" value="3"><label for="star3">&#9733;</label>
                        <input type="radio" name="rating" id="star2" value="2"><label for="star2">&#9733;</label>
                        <input type="radio" name="rating" id="star1" value="1"><label for="star1">&#9733;</label>
                    </div>

                    <!-- Comment for driver -->
                    <div class="form-group mt-3">
                        <label for="driverComment">Leave a comment for the driver:</label>
                        <textarea class="form-control" id="driverComment" name="comment" rows="3" placeholder="Write your feedback..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Submit Rating</button>
                </form>
            @endif
        </div>
    </div>
</div>

<!-- Bootstrap and jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

</body>
</html>
