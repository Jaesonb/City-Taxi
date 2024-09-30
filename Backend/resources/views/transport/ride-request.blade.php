<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request a Ride</title>

    <!-- Leaflet CSS and JavaScript -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <!-- Bootstrap for styling -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        #map {
            height: 400px;
            width: 100%;
        }
        .dropdown-menu {
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            position: absolute;
        }
        .dropdown-item {
            cursor: pointer;
        }
        .position-relative {
            position: relative;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">City Taxi - Ride Request</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('transport.post-trip') }}">My Rides</a>
                </li>
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
<div class="container">
    <h2>Request a Ride</h2>

    <!-- Display error messages if they exist -->
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Display validation errors -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Ride Request Form -->
    <form action="{{ route('trip.store') }}" method="POST">
        @csrf
        <div class="form-group position-relative">
            <label for="pickup">Pickup Location:</label>
            <input type="text" id="pickup" name="pickup_location" class="form-control" placeholder="Enter your pickup location" autocomplete="off">
            <ul id="pickup-suggestions" class="dropdown-menu"></ul>
            <input type="hidden" id="pickup_latitude" name="pickup_latitude">
            <input type="hidden" id="pickup_longitude" name="pickup_longitude">
        </div>
        <div class="form-group position-relative">
            <label for="destination">Destination:</label>
            <input type="text" id="destination" name="dropoff_location" class="form-control" placeholder="Enter your destination" autocomplete="off">
            <ul id="destination-suggestions" class="dropdown-menu"></ul>
            <input type="hidden" id="dropoff_latitude" name="dropoff_latitude">
            <input type="hidden" id="dropoff_longitude" name="dropoff_longitude">
        </div>
        <div class="form-group">
            <label for="driver-list">Driver:</label>
            <select id="driver-list" name="driver_id" class="form-control">
                <option value="">Select a Driver</option>
                @foreach($drivers as $driver)
                    <option value="{{ $driver->id }}">
                        {{ $driver->name }} - {{ $driver->vehicle_number }}
                        @if(isset($driver->distance))
                            ({{ round($driver->distance, 2) }} km away)
                        @endif
                    </option>
                @endforeach
            </select>
        </div>
        <input type="hidden" name="pickup_time" value="{{ now() }}">
        <input type="hidden" name="status" value="PENDING">

        <button type="submit" class="btn btn-primary mt-3">Request Ride</button>
    </form>

    <!-- Map -->
    <div id="map"></div>
</div>

<!-- Bootstrap and jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    let map, pickupMarker, destinationMarker;

    // Initialize the map
    function initMap() {
        map = L.map('map').setView([51.505, -0.09], 13); // Default to London

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);
    }

    // Geocode address and place marker
    function geocodeAddress(address, type) {
        axios.get(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
            .then(response => {
                if (response.data.length > 0) {
                    const location = response.data[0];
                    const latLng = [location.lat, location.lon];

                    if (type === 'pickup') {
                        $('#pickup_latitude').val(location.lat);
                        $('#pickup_longitude').val(location.lon);
                        placeMarker(latLng, 'Pickup Location', 'pickup');
                        loadAvailableDrivers(location.lat, location.lon);
                    } else if (type === 'destination') {
                        $('#dropoff_latitude').val(location.lat);
                        $('#dropoff_longitude').val(location.lon);
                        placeMarker(latLng, 'Destination Location', 'destination');
                    }
                    map.setView(latLng, 14);
                } else {
                    alert('Location not found.');
                }
            })
            .catch(error => console.error('Error geocoding address:', error));
    }

    // Place marker on the map
    function placeMarker(location, title, type) {
        const marker = type === 'pickup' ? pickupMarker : destinationMarker;

        if (marker) {
            marker.setLatLng(location);
        } else {
            const newMarker = L.marker(location).addTo(map).bindPopup(title).openPopup();
            if (type === 'pickup') {
                pickupMarker = newMarker;
            } else {
                destinationMarker = newMarker;
            }
        }
    }

    // Fetch and display location suggestions
    function fetchSuggestions(query, type) {
        return axios.get(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
            .then(response => response.data)
            .catch(error => {
                console.error('Error fetching suggestions:', error);
                return [];
            });
    }

    function showSuggestions(suggestions, type) {
        const suggestionsList = type === 'pickup' ? $('#pickup-suggestions') : $('#destination-suggestions');
        suggestionsList.empty();

        suggestions.forEach(suggestion => {
            const listItem = $('<li class="dropdown-item"></li>').text(suggestion.display_name).click(() => {
                $(`#${type}`).val(suggestion.display_name);
                suggestionsList.empty().hide();
                geocodeAddress(suggestion.display_name, type);
            });
            suggestionsList.append(listItem);
        });

        suggestionsList.show();
    }

    // Handle input events for pickup and destination
    function loadAvailableDrivers(pickupLat, pickupLng) {
        if (pickupLat && pickupLng) {
            // Show a loading spinner or message
            $('#driver-list').html('<option>Loading drivers...</option>');

            $.ajax({
                url: '{{ route('drivers.byDistance') }}',
                method: 'GET',
                data: {
                    pickup_latitude: pickupLat,
                    pickup_longitude: pickupLng
                },
                success: function (response) {
                    $('#driver-list').empty();
                    $('#driver-list').append('<option value="">Select a Driver</option>');

                    response.drivers.forEach(driver => {
                        $('#driver-list').append(
                            `<option value="${driver.id}">
                                ${driver.name} - ${driver.vehicle_number}
                                (${driver.distance.toFixed(2)} km away)
                            </option>`
                        );
                    });
                },
                error: function (error) {
                    console.error('Error loading drivers:', error);
                    $('#driver-list').html('<option>Error loading drivers</option>');
                }
            });
        }
    }

    // Handle input events for pickup location
    $('#pickup').on('input', function () {
        const query = $(this).val();
        if (query.length > 2) {
            fetchSuggestions(query, 'pickup').then(suggestions => {
                showSuggestions(suggestions, 'pickup');
            });
        } else {
            $('#pickup-suggestions').empty().hide();
        }
    });

    $('#destination').on('input', function () {
        const query = $(this).val();
        if (query.length > 2) {
            fetchSuggestions(query, 'destination').then(suggestions => showSuggestions(suggestions, 'destination'));
        } else {
            $('#destination-suggestions').empty().hide();
        }
    });

    $('#pickup').on('change', function () {
        const pickupLat = $('#pickup_latitude').val();
        const pickupLng = $('#pickup_longitude').val();

        if (pickupLat && pickupLng) {
            // Show a loading spinner or message
            $('#driver-list').html('<option>Loading drivers...</option>');

            $.ajax({
                url: '{{ route('drivers.byDistance') }}',
                method: 'GET',
                data: {
                    pickup_latitude: pickupLat,
                    pickup_longitude: pickupLng
                },
                success: function (response) {
                    $('#driver-list').empty();
                    $('#driver-list').append('<option value="">Select a Driver</option>');

                    response.drivers.forEach(driver => {
                        $('#driver-list').append(
                            `<option value="${driver.id}">
                                ${driver.name} - ${driver.vehicle_number}
                                (${driver.distance.toFixed(2)} km away)
                            </option>`
                        );
                    });
                },
                error: function (error) {
                    console.error('Error loading drivers:', error);
                    $('#driver-list').html('<option>Error loading drivers</option>');
                }
            });
        }
    });

    // Initialize the map on page load
    initMap();
</script>

</body>
</html>
