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
                            <input type="text" vehicle_number="vehicle_number" id="vehicle_number" value="{{ old('vehicle_number') }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('vehicle_number')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Status') }}</label>
                            <input type="text" name="status" id="status" value="{{ old('status') }}"class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300"required>
                        </div>


                        <div class="mb-4">
                            <label for="car color" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Car Color') }}</label>
                            <input type="color" name="car color" id="" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                        </div>

                        <div class="mb-4">
                            <label for="car model" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Car Model') }}</label>
                            <input type="text" name="car model" id="" value="{{ old('car model') }}"class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                        </div>

                        <div class="mb-4">
                            <label for="car brand" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Car Brand') }}</label>
                            <input type="text" name="car brand" id="" value="{{ old('car brand') }}"class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                        </div>

                        <div class="mb-4">
                            <label for="latitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Latitude') }}</label>
                            <input type="text" name="latitude" id="latitude" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('latitude')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="longitude" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Longitude') }}</label>
                            <input type="text" name="longitude" id="longitude" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('longitude')
                            <span class="text-sm text-red-600">{{ $message }}</span>
                        @enderror
                        </div>


                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">{{ __('Driver_Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
