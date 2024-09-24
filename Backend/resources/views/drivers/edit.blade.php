<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Driver') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Edit Driver Form -->
                    <form action="{{ route('drivers.update', $driver->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Name') }}</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $driver->name) }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('name')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('New Password') }}</label>
                            <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300">
                            @error('password')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                            <p class="text-sm text-gray-500 dark:text-gray-400">Leave blank if you don't want to change the password.</p>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Confirm New Password') }}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300">
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Email') }}</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $driver->email) }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('email')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Phone Number') }}</label>
                            <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $driver->phone_number) }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('phone_number')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="vehicle_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Vehicle Number') }}</label>
                            <input type="text" name="vehicle_number" id="vehicle_number" value="{{ old('vehicle_number', $driver->vehicle_number) }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
                            <input type="text" name="status" id="status" value="{{ old('status', $driver->status) }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                        </div>

                        <div class="mb-4">
                            <label for="car_color" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Car Color') }}</label>
                            <input type="color" name="car_color" id="car_color" value="{{ old('car_color', $driver->carcolor) }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                        </div>

                        <div class="mb-4">
                            <label for="car_model" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Car Model') }}</label>
                            <input type="text" name="car_model" id="car_model" value="{{ old('car_model', $driver->carmodel) }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                        </div>

                        <div class="mb-4">
                            <label for="car_brand" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Car Brand') }}</label>
                            <input type="text" name="car_brand" id="car_brand" value="{{ old('car_brand', $driver->carbrand) }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                        </div>

                        <div class="mb-4">
                            <label for="latitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Latitude') }}</label>
                            <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $driver->latitude) }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                        </div>

                        <div class="mb-4">
                            <label for="longitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Longitude') }}</label>
                            <input type="text" name="longitude" id="longitude" value="{{ old('longitude', $driver->longitude) }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">{{ __('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
