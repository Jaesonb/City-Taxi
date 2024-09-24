<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Total Drivers -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-xl font-semibold">{{ __('Total Drivers') }}</h3>
                        <p class="mt-4 text-3xl">{{ $totalDrivers }}</p>
                    </div>
                </div>

                <!-- Active Trips -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-xl font-semibold">{{ __('Active Trips') }}</h3>
                        <p class="mt-4 text-3xl">{{ $activeTrips }}</p>
                    </div>
                </div>

                <!-- Total Payments -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-xl font-semibold">{{ __('Total Payments') }}</h3>
                        <p class="mt-4 text-3xl">{{ $totalPayments }} {{ __('LKR') }}</p>
                    </div>
                </div>

                <!-- Completed Trips -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-xl font-semibold">{{ __('Completed Trips') }}</h3>
                        <p class="mt-4 text-3xl">{{ $completedTrips }}</p>
                    </div>
                </div>

                <!-- Pending Driver Approvals -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-xl font-semibold">{{ __('Available Drivers') }}</h3>
                        <p class="mt-4 text-3xl">{{ $availableDrivers }}</p>
                    </div>
                </div>

                <!-- Revenue -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-xl font-semibold">{{ __('Revenue') }}</h3>
                        <p class="mt-4 text-3xl">{{ $revenue }} {{ __('LKR') }}</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
