<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Trip') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Trip creation form --}}
                    <form action="{{ route('trips.store') }}" method="POST">
                        @csrf

                        {{-- Passenger Dropdown --}}
                        <div class="mb-4">
                            <label for="passenger_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Passenger') }}</label>
                            <select name="passenger_id" id="passenger_id" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                                <option value="">{{ __('Select Passenger') }}</option>
                                @foreach($passengers as $passenger)
                                    <option value="{{ $passenger->id }}" {{ old('passenger_id') == $passenger->id ? 'selected' : '' }}>
                                        {{ $passenger->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('passenger_id')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Driver Dropdown --}}
                        <div class="mb-4">
                            <label for="driver_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Driver') }}</label>
                            <select name="driver_id" id="driver_id" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                                <option value="">{{ __('Select Driver') }}</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                        {{ $driver->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('driver_id')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Pickup Location --}}
                        <div class="mb-4">
                            <label for="pickup_location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Pickup Location') }}</label>
                            <input type="text" name="pickup_location" id="pickup_location" value="{{ old('pickup_location') }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required readonly>
                            @error('pickup_location')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="pickup_latitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Pickup Latitude') }}</label>
                            <input type="text" name="pickup_latitude" id="pickup_latitude" value="{{ old('pickup_latitude') }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required readonly>
                            @error('pickup_latitude')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="pickup_longitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Pickup Longitude') }}</label>
                            <input type="text" name="pickup_longitude" id="pickup_longitude" value="{{ old('pickup_longitude') }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required readonly>
                            @error('pickup_longitude')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Map Section --}}
                        <div class="mb-4">
                            <div id="map" style="height: 400px;"></div>
                        </div>

                        {{-- Dropoff Location --}}
                        <div class="mb-4">
                            <label for="dropoff_location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Dropoff Location') }}</label>
                            <input type="text" name="dropoff_location" id="dropoff_location" value="{{ old('dropoff_location') }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required readonly>
                            @error('dropoff_location')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="dropoff_latitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Dropoff Latitude') }}</label>
                            <input type="text" name="dropoff_latitude" id="dropoff_latitude" value="{{ old('dropoff_latitude') }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required readonly>
                            @error('dropoff_latitude')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="dropoff_longitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Dropoff Longitude') }}</label>
                            <input type="text" name="dropoff_longitude" id="dropoff_longitude" value="{{ old('dropoff_longitude') }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required readonly>
                            @error('dropoff_longitude')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Pickup Time --}}
                        <div class="mb-4">
                            <label for="pickup_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Pickup Time') }}</label>
                            <input type="datetime-local" name="pickup_time" id="pickup_time" value="{{ old('pickup_time') }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('pickup_time')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">{{ __('Save Trip') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Leaflet.js Map Script --}}
    <script>
        var map = L.map('map').setView([7.8731, 80.7718], 7); // Centered on Sri Lanka

        // Load OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        var pickupMarker, dropoffMarker;

        // Geocoder search for pickup location
        var pickupGeocoder = L.Control.geocoder({
            defaultMarkGeocode: false
        }).on('markgeocode', function(e) {
            var latlng = e.geocode.center;

            if (pickupMarker) {
                map.removeLayer(pickupMarker);
            }
            pickupMarker = L.marker(latlng).addTo(map);
            map.setView(latlng, 15);

            document.getElementById('pickup_latitude').value = latlng.lat;
            document.getElementById('pickup_longitude').value = latlng.lng;
            document.getElementById('pickup_location').value = e.geocode.name; // Set location name
        }).addTo(map);

        // Geocoder search for dropoff location
        var dropoffGeocoder = L.Control.geocoder({
            defaultMarkGeocode: false
        }).on('markgeocode', function(e) {
            var latlng = e.geocode.center;

            if (dropoffMarker) {
                map.removeLayer(dropoffMarker);
            }
            dropoffMarker = L.marker(latlng).addTo(map);
            map.setView(latlng, 15);

            document.getElementById('dropoff_latitude').value = latlng.lat;
            document.getElementById('dropoff_longitude').value = latlng.lng;
            document.getElementById('dropoff_location').value = e.geocode.name; // Set location name
        }).addTo(map);

        // Click event to manually set pickup and dropoff locations
        function onMapClick(e) {
            if (!pickupMarker) {
                pickupMarker = L.marker(e.latlng).addTo(map);
                document.getElementById('pickup_latitude').value = e.latlng.lat;
                document.getElementById('pickup_longitude').value = e.latlng.lng;
                document.getElementById('pickup_location').value = `Lat: ${e.latlng.lat}, Lng: ${e.latlng.lng}`;
            } else if (!dropoffMarker) {
                dropoffMarker = L.marker(e.latlng).addTo(map);
                document.getElementById('dropoff_latitude').value = e.latlng.lat;
                document.getElementById('dropoff_longitude').value = e.latlng.lng;
                document.getElementById('dropoff_location').value = `Lat: ${e.latlng.lat}, Lng: ${e.latlng.lng}`;
            }
        }

        map.on('click', onMapClick);

    </script>
</x-app-layout>
