{{-- resources/views/drivers/trips.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Driver Trips and Payments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">{{ __('Driver Name') }}: {{ $driver->name }}</h3>
                    <h4 class="text-md mb-4">{{ __('Email') }}: {{ $driver->email }}</h4>

                    <h4 class="text-md font-semibold mb-4">{{ __('Trips and Payments') }}</h4>

                    @forelse($driver->trips as $trip)
                        <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-md">
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <strong>{{ __('Trip') }}:</strong> {{ $trip->pickup_location }} to {{ $trip->dropoff_location }}
                                </div>
                                <div>
                                    <strong>{{ __('Date') }}:</strong> {{ $trip->created_at->format('Y-m-d') }}
                                </div>
                                <div>
                                    <strong>{{ __('Fare') }}:</strong> {{ $trip->payment->amount ?? 'N/A' }}
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mt-2">
                                <div>
                                    <strong>{{ __('Payment Status') }}:</strong> {{ ucfirst($trip->payment->payment_status ?? 'N/A') }}
                                </div>
                                <div>
                                    <strong>{{ __('Payment Method') }}:</strong> {{ ucfirst($trip->payment->payment_method ?? 'N/A') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>{{ __('No trips found for this driver.') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
