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
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.edit') }}">Profile</a> <!-- Link to driver profile page -->
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                       Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h2>Nearby Trip Requests</h2>
        <div id="trip-requests" class="list-group"></div>
    </div>

    <!-- Bootstrap and jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        // Use driver data from Laravel passed as a Blade variable
        const driverId = {{ $driver->id }};
        const driverLatitude = {{ $driver->latitude }}; // Use driver's actual latitude
        const driverLongitude = {{ $driver->longitude }}; // Use driver's actual longitude
        const radius = 5; // Search within 5km radius (can be adjusted)

        // Function to fetch nearby trips
        function fetchNearbyTrips() {
            axios.get('/api/trips/nearby', {
                params: {
                    driver_latitude: driverLatitude,
                    driver_longitude: driverLongitude,
                    radius: radius
                }
            })
            .then(function(response) {
                const trips = response.data;
                const tripList = $('#trip-requests');
                tripList.empty();

                if (trips.length === 0) {
                    tripList.append('<p class="alert alert-info">No trips available nearby.</p>');
                    return;
                }

                trips.forEach(trip => {
                    tripList.append(`
                        <div class="list-group-item">
                            <h5>Pickup: ${trip.pickup_location}</h5>
                            <p>Passenger: ${trip.passenger.name}</p>
                            <p>Fare: $${trip.fare.toFixed(2)}</p>
                            <button class="btn btn-primary" onclick="acceptTrip(${trip.id})">Accept Trip</button>
                        </div>
                    `);
                });
            })
            .catch(function(error) {
                console.error('Error fetching trips:', error);
            });
        }

        // Function to accept a trip
        function acceptTrip(tripId) {
            axios.post('/api/trips/accept', {
                trip_id: tripId,
                driver_id: driverId
            })
            .then(function(response) {
                alert('Trip accepted successfully!');
                fetchNearbyTrips(); // Refresh trip list
            })
            .catch(function(error) {
                console.error('Error accepting trip:', error);
                alert('Failed to accept trip.');
            });
        }

        // Fetch nearby trips when the page loads
        $(document).ready(function() {
            fetchNearbyTrips();
        });
    </script>
</body>

</html>
