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
        /* Styling for the map */
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
        #ride-status {
            margin-top: 20px;
        }
        .position-relative {
            position: relative;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Request a Ride</h2>

    <!-- Ride Request Form -->
    <form id="rideRequestForm">
        <div class="form-group position-relative">
            <label for="pickup">Pickup Location:</label>
            <input type="text" id="pickup" class="form-control" placeholder="Enter your pickup location" autocomplete="off">
            <ul id="pickup-suggestions" class="dropdown-menu"></ul>
        </div>
        <div class="form-group position-relative">
            <label for="destination">Destination:</label>
            <input type="text" id="destination" class="form-control" placeholder="Enter your destination" autocomplete="off">
            <ul id="destination-suggestions" class="dropdown-menu"></ul>
        </div>
        <!-- Dropdown for selecting a driver -->
        <select id="driver-list" class="form-control">
            <option value="">Select a Driver</option>
        </select>
        <button type="submit" class="btn btn-primary mt-3">Request Ride</button>
    </form>

    <!-- Map to display pickup, destination, and driver -->
    <div id="map"></div>

    <!-- Ride status and actions -->
    <div id="ride-status" class="alert alert-info" style="display: none;">
        Waiting for a driver to accept your ride...
    </div>
</div>

<!-- Bootstrap and jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    let map;
    let pickupMarker;
    let destinationMarker;
    let driverMarker;
    let pickupLocation;
    let destinationLocation;
    let selectedDriverId = null;

    function initMap() {
        // Initialize the map centered on a default location (adjust as needed)
        map = L.map('map').setView([51.505, -0.09], 13); // Default to London

        // Load OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
    }

    function geocodeAddress(address, type) {
        axios.get(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
            .then(response => {
                if (response.data.length > 0) {
                    const location = response.data[0];
                    const latLng = [location.lat, location.lon];

                    if (type === 'pickup') {
                        pickupLocation = latLng;
                        placeMarker(latLng, 'Pickup Location', 'pickup');
                    } else if (type === 'destination') {
                        destinationLocation = latLng;
                        placeMarker(latLng, 'Destination Location', 'destination');
                    }

                    map.setView(latLng, 14);
                } else {
                    alert('Location not found.');
                }
            })
            .catch(error => console.error('Error geocoding address:', error));
    }

    function placeMarker(location, title, type) {
        if (type === 'pickup') {
            if (pickupMarker) {
                pickupMarker.setLatLng(location);
            } else {
                pickupMarker = L.marker(location).addTo(map).bindPopup(title).openPopup();
            }
        } else if (type === 'destination') {
            if (destinationMarker) {
                destinationMarker.setLatLng(location);
            } else {
                destinationMarker = L.marker(location).addTo(map).bindPopup(title).openPopup();
            }
        }
    }

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
            fetchSuggestions(query, 'destination').then(suggestions => {
                showSuggestions(suggestions, 'destination');
            });
        } else {
            $('#destination-suggestions').empty().hide();
        }
    });

    // Handle form submission for requesting a ride
    $('#rideRequestForm').on('submit', function (e) {
        e.preventDefault();

        if (!pickupLocation || !destinationLocation || !selectedDriverId) {
            alert('Please select pickup, destination, and driver.');
            return;
        }

        $('#ride-status').show();

        // Send ride request to the server (Trip API: Create new trip)
        axios.post('/api/trips', {
            passenger_id: 1,  // Replace with the actual logged-in passenger ID
            driver_id: selectedDriverId,
            pickup_location: $('#pickup').val(),
            pickup_latitude: pickupLocation[0],
            pickup_longitude: pickupLocation[1],
            dropoff_location: $('#destination').val(),
            dropoff_latitude: destinationLocation[0],
            dropoff_longitude: destinationLocation[1],
            pickup_time: new Date().toISOString(),  // Current time
            fare: 20.00,  // Example fare, this could be dynamically calculated or set later
            status: 'ONGOING',
        })
        .then(function (response) {
            if (response.data.message === 'Trip created successfully.') {
                // Once trip is created, start listening for ride updates
                listenForDriverLocation(response.data.trip.id);
            } else {
                alert('Error requesting a ride');
            }
        })
        .catch(function (error) {
            console.error('Error requesting a ride:', error);
        });
    });

    function listenForDriverLocation(tripId) {
        Echo.channel('ride.' + tripId)
            .listen('DriverLocationUpdated', (event) => {
                const driverLocation = [event.latitude, event.longitude];

                if (!driverMarker) {
                    driverMarker = L.marker(driverLocation, {
                        icon: L.icon({
                            iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41]
                        })
                    }).addTo(map).bindPopup('Driver').openPopup();
                } else {
                    driverMarker.setLatLng(driverLocation);
                }

                map.setView(driverLocation, 14);

                // If trip is completed, show payment page
                if (event.trip_status === 'COMPLETED') {
                    window.location.href = "{{ route('transport.post-trip') }}";
                }
            });
    }

    // Initialize the map
    initMap();
</script>

</body>
</html>
