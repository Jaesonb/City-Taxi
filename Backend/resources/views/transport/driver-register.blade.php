<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Registration</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Leaflet CSS (for map) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <style>
        /* Custom styles for form */
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }

        .form-container {
            margin-top: 50px;
            max-width: 800px;
            background-color: white;
            padding: 30px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        /* Map container */
        #map {
            height: 400px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12">
                <div class="form-container">
                    <h2>Driver Registration</h2>

                    <!-- Show success or error messages -->
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Driver Registration Form -->
                    <form action="{{ route('driver.register') }}" method="POST">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Enter your name" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Enter your password again" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="phone_number" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="Enter your phone number" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="vehicle_number" class="form-label">Vehicle Number</label>
                            <input type="text" class="form-control" id="vehicle_number" name="vehicle_number" value="{{ old('vehicle_number') }}" placeholder="Enter your vehicle number" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="color" class="form-label">Vehicle Color</label>
                            <input type="text" class="form-control" id="color" name="color" value="{{ old('color') }}" placeholder="Enter vehicle color" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="model" class="form-label">Vehicle Model</label>
                            <input type="text" class="form-control" id="model" name="model" value="{{ old('model') }}" placeholder="Enter vehicle model" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="brand" class="form-label">Vehicle Brand</label>
                            <input type="text" class="form-control" id="brand" name="brand" value="{{ old('brand') }}" placeholder="Enter vehicle brand" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="status" class="form-label">Driver Status</label>
                            <select class="form-control" name="status" id="status" required>
                                <option value="available">Available</option>
                                <option value="busy">Busy</option>
                            </select>
                        </div>

                        <!-- Latitude, Longitude & Map -->
                        <div class="form-group mb-3">
                            <label for="latitude" class="form-label">Latitude</label>
                            <input type="text" class="form-control" id="latitude" name="latitude" readonly required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input type="text" class="form-control" id="longitude" name="longitude" readonly required>
                        </div>

                        <div id="map"></div>

                        <button type="submit" class="btn btn-success w-100">Register Driver</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Leaflet JS for map -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <!-- Custom JS -->
    {{-- <script src="{{ asset('public/transport/script.js') }}"></script> --}}

    <!-- Map Initialization Script -->
    <script>
        var map = L.map('map').setView([7.8731, 80.7718], 7); // Centered on Sri Lanka

        // Load OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var marker;

        // Click event to set marker and get coordinates
        map.on('click', function(e) {
            if (marker) {
                map.removeLayer(marker);
            }
            marker = L.marker(e.latlng).addTo(map);
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
        });
    </script>
</body>

</html>
