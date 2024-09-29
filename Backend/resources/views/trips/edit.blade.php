<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Trip') }}
        </h2>
    </x-slot>

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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- Edit Trip Form --}}
                    <form action="{{ route('trips.update', $trip->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Passenger Dropdown --}}
                        <div class="mb-4">
                            <label for="passenger_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Passenger') }}</label>
                            <select name="passenger_id" id="passenger_id" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                                <option value="">{{ __('Select Passenger') }}</option>
                                @foreach($passengers as $passenger)
                                    <option value="{{ $passenger->id }}" {{ old('passenger_id', $trip->passenger_id) == $passenger->id ? 'selected' : '' }}>
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
                                    <option value="{{ $driver->id }}" {{ old('driver_id', $trip->driver_id) == $driver->id ? 'selected' : '' }}>
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
                            <input type="text" name="pickup_location" id="pickup_location" value="{{ old('pickup_location', $trip->pickup_location) }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required readonly>
                            @error('pickup_location')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Hidden Latitude and Longitude for Pickup --}}
                        <input type="hidden" name="pickup_latitude" value="{{ old('pickup_latitude', $trip->pickup_latitude) }}">
                        <input type="hidden" name="pickup_longitude" value="{{ old('pickup_longitude', $trip->pickup_longitude) }}">

                        {{-- Dropoff Location --}}
                        <div class="mb-4">
                            <label for="dropoff_location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Dropoff Location') }}</label>
                            <input type="text" name="dropoff_location" id="dropoff_location" value="{{ old('dropoff_location', $trip->dropoff_location) }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required readonly>
                            @error('dropoff_location')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Hidden Latitude and Longitude for Dropoff --}}
                        <input type="hidden" name="dropoff_latitude" value="{{ old('dropoff_latitude', $trip->dropoff_latitude) }}">
                        <input type="hidden" name="dropoff_longitude" value="{{ old('dropoff_longitude', $trip->dropoff_longitude) }}">

                        {{-- Pickup Time --}}
                        <div class="mb-4">
                            <label for="pickup_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Pickup Time') }}</label>
                            <input type="datetime-local" name="pickup_time" id="pickup_time" value="{{ old('pickup_time', \Carbon\Carbon::parse($trip->pickup_time)->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('pickup_time')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Dropoff Time --}}
                        <div class="mb-4">
                            <label for="dropoff_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Dropoff Time') }}</label>
                            <input type="datetime-local" name="dropoff_time" id="dropoff_time" value="{{ old('dropoff_time', \Carbon\Carbon::parse($trip->dropoff_time)->format('Y-m-d\TH:i')) }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300">
                            @error('dropoff_time')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Trip Status --}}
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                                <option value="PENDING" {{ old('status', $trip->status) == 'PENDING' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                <option value="CONFIRMED" {{ old('status', $trip->status) == 'CONFIRMED' ? 'selected' : '' }}>{{ __('Confirmed') }}</option>
                                <option value="COMPLETED" {{ old('status', $trip->status) == 'COMPLETED' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                                <option value="CANCELED" {{ old('status', $trip->status) == 'CANCELED' ? 'selected' : '' }}>{{ __('Canceled') }}</option>
                            </select>
                            @error('status')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">{{ __('Update Trip') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
