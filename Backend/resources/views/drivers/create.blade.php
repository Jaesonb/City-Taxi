<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Driver') }}
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

                    {{-- Driver creation form --}}
                    <form action="{{ route('drivers.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Name') }}</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('name')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Password') }}</label>
                            <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('password')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Confirm Password') }}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('password_confirmation')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Email') }}</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('email')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Phone Number') }}</label>
                            <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('phone_number')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="vehicle_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Vehicle Number') }}</label>
                            <input type="text" name="vehicle_number" id="vehicle_number" value="{{ old('vehicle_number') }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('vehicle_number')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                                <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="busy" {{ old('status') == 'busy' ? 'selected' : '' }}>Busy</option>
                            </select>
                            @error('status')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="color" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Car Color') }}</label>
                            <input type="text" name="color" id="color" value="{{ old('color') }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('color')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="model" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Car Model') }}</label>
                            <input type="text" name="model" id="model" value="{{ old('model') }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('model')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="brand" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Car Brand') }}</label>
                            <input type="text" name="brand" id="brand" value="{{ old('brand') }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('brand')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="latitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Latitude') }}</label>
                            <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required readonly>
                            @error('latitude')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="longitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Longitude') }}</label>
                            <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required readonly>
                            @error('longitude')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Map container -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Select Location') }}</label>
                            <div id="map" style="height: 300px;"></div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">{{ __('Save Driver') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Leaflet.js and Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

    <!-- Map Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize map
            var map = L.map('map').setView([51.505, -0.09], 13); // Default view, adjust as necessary

            // Set up the OSM layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Create marker variable
            var marker;

            // Event listener for map click
            map.on('click', function (e) {
                // Get coordinates from the click event
                var lat = e.latlng.lat;
                var lng = e.latlng.lng;

                // If a marker already exists, remove it
                if (marker) {
                    map.removeLayer(marker);
                }

                // Add marker at the clicked location
                marker = L.marker([lat, lng]).addTo(map);

                // Set latitude and longitude input values
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
            });
        });
    </script>
</x-app-layout>
