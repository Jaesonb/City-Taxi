<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request a Ride</title>
    <!-- Google Maps API (Replace YOUR_GOOGLE_MAPS_API_KEY with your real key) -->
    <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&libraries=places"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Styling for map */
        #map {
            height: 400px;
            width: 100%;
        }

        #ride-status {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Request a Ride</h2>

        <!-- Ride Request Form -->
        <form id="rideRequestForm">
            <div class="form-group">
                <label for="pickup">Pickup Location:</label>
                <input type="text" id="pickup" class="form-control" placeholder="Enter your pickup location">
            </div>
            <div class="form-group">
                <label for="destination">Destination:</label>
                <input type="text" id="destination" class="form-control" placeholder="Enter your destination">
            </div>
            <button type="submit" class="btn btn-primary">Request Ride</button>
        </form>

        <!-- Map to display driver's location -->
        <div id="map"></div>

        <!-- Ride status and actions -->
        <div id="ride-status" class="alert alert-info" style="display: none;">
            Waiting for a driver to accept your ride...
        </div>
    </div>

    <!-- Bootstrap and jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- Script for requesting a ride and tracking driver's location -->
    <script>
        let map;
        let marker;
        let driverMarker;
        let pickupLocation;
        let destinationLocation;

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: -34.397, lng: 150.644 }, // Default location
                zoom: 8
            });

            const inputPickup = document.getElementById('pickup');
            const inputDestination = document.getElementById('destination');

            // Autocomplete for pickup and destination locations
            const autocompletePickup = new google.maps.places.Autocomplete(inputPickup);
            const autocompleteDestination = new google.maps.places.Autocomplete(inputDestination);

            autocompletePickup.addListener('place_changed', function () {
                const place = autocompletePickup.getPlace();
                pickupLocation = place.geometry.location;
                placeMarker(pickupLocation);
            });

            autocompleteDestination.addListener('place_changed', function () {
                const place = autocompleteDestination.getPlace();
                destinationLocation = place.geometry.location;
            });
        }

        // Place marker for pickup location
        function placeMarker(location) {
            if (marker) {
                marker.setPosition(location);
            } else {
                marker = new google.maps.Marker({
                    position: location,
                    map: map,
                    title: 'Pickup Location'
                });
            }
            map.setCenter(location);
            map.setZoom(14);
        }

        // Listen for form submission
        $('#rideRequestForm').on('submit', function (e) {
            e.preventDefault();

            if (!pickupLocation || !destinationLocation) {
                alert('Please enter both pickup and destination locations.');
                return;
            }

            $('#ride-status').show();

            // Send ride request to the server
            axios.post('/api/request-ride', {
                pickup_lat: pickupLocation.lat(),
                pickup_lng: pickupLocation.lng(),
                destination_lat: destinationLocation.lat(),
                destination_lng: destinationLocation.lng()
            })
                .then(function (response) {
                    if (response.data.success) {
                        // Listen for driver's location updates (real-time)
                        listenForDriverLocation(response.data.ride_id);
                    } else {
                        alert('Error requesting a ride');
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        });

        // Listen for driver's location updates using WebSockets
        function listenForDriverLocation(rideId) {
            // Example using Laravel Echo and Pusher for real-time updates
            Echo.channel('ride.' + rideId)
                .listen('DriverLocationUpdated', (event) => {
                    const driverLocation = { lat: event.latitude, lng: event.longitude };

                    if (!driverMarker) {
                        driverMarker = new google.maps.Marker({
                            position: driverLocation,
                            map: map,
                            icon: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
                            title: 'Driver'
                        });
                    } else {
                        driverMarker.setPosition(driverLocation);
                    }

                    map.setCenter(driverLocation);
                });
        }

        // Initialize the map
        initMap();
    </script>

</body>

</html>