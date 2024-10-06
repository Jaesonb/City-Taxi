<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Dashboard - Accept Trips</title>

    <!-- Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <!-- Navbar with Logout and Profile options -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">City Taxi - Driver Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <!-- Profile Link -->
                {{-- <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.edit') }}">Profile</a>
                </li> --}}

                <!-- Logout Link -->
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('driver.logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('driver.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>My Trips</h2>

        @if ($driver->trips->isEmpty())
            <p class="alert alert-info">You have no trips at the moment.</p>
        @else
            <div class="list-group">
                @foreach ($driver->trips as $trip)
                    <div class="list-group-item">
                        <h5 class="mb-1">Trip to {{ $trip->dropoff_location }}</h5>
                        <p><strong>Pickup Location:</strong> {{ $trip->pickup_location }}</p>
                        <p><strong>Pickup Time:</strong> {{ $trip->pickup_time }}</p>
                        <p><strong>Status:</strong> {{ ucfirst($trip->status) }}</p>

                        @if ($trip->status == 'PENDING')
                            <div class="mt-2">
                                <a href="{{ route('drivers.accept_trip', $trip->id) }}" class="btn btn-success btn-sm">Accept</a>
                                <a href="{{ route('drivers.decline_trip', $trip->id) }}" class="btn btn-danger btn-sm">Decline</a>
                            </div>
                        @elseif ($trip->status == 'CONFIRMED')
                            <p class="text-success">You have accepted this trip.</p>
                        @elseif ($trip->status == 'CANCELLED')
                            <p class="text-danger">You have declined this trip.</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Bootstrap and jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

</body>
</html>
