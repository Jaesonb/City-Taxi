<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Payment') }}
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

                    {{-- Payment creation form --}}
                    <form action="{{ route('payments.store') }}" method="POST">
                        @csrf
                        {{-- Trip Dropdown --}}
                        <div class="mb-4">
                            <label for="trip_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Trip') }}</label>
                            <select name="trip_id" id="trip_id" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                                <option value="">{{ __('Select Trip') }}</option>
                                @foreach($trips as $trip)
                                    <option value="{{ $trip->id }}" {{ old('trip_id') == $trip->id ? 'selected' : '' }}>
                                        {{ $trip->id }} - {{ $trip->pickup_location }} to {{ $trip->dropoff_location }}
                                    </option>
                                @endforeach
                            </select>
                            @error('trip_id')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Amount Field (Auto-populated) --}}
                        <div class="mb-4">
                            <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Amount (Fare)') }}</label>
                            <input type="text" name="amount" id="amount" value="{{ old('amount') }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('amount')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Payment Method Dropdown --}}
                        <div class="mb-4">
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Payment Method') }}</label>
                            <select name="payment_method" id="payment_method" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                                <option value="CASH" {{ old('payment_method') == 'CASH' ? 'selected' : '' }}>{{ __('Cash') }}</option>
                                <option value="CREDIT_CARD" {{ old('payment_method') == 'CREDIT_CARD' ? 'selected' : '' }}>{{ __('Credit Card') }}</option>
                                <option value="DEBIT_CARD" {{ old('payment_method') == 'DEBIT_CARD' ? 'selected' : '' }}>{{ __('Debit Card') }}</option>
                                <option value="ONLINE" {{ old('payment_method') == 'ONLINE' ? 'selected' : '' }}>{{ __('Online') }}</option>
                            </select>
                            @error('payment_method')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Payment Status Dropdown --}}
                        <div class="mb-4">
                            <label for="payment_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Payment Status') }}</label>
                            <select name="payment_status" id="payment_status" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                                <option value="PENDING" {{ old('payment_status') == 'PENDING' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                <option value="PAID" {{ old('payment_status') == 'PAID' ? 'selected' : '' }}>{{ __('Paid') }}</option>
                                <option value="FAILED" {{ old('payment_status') == 'FAILED' ? 'selected' : '' }}>{{ __('Failed') }}</option>
                            </select>
                            @error('payment_status')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">{{ __('Save Payment') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Fetch fare when a trip is selected
            $('#trip_id').change(function() {
                var tripId = $(this).val();

                if (tripId) {
                    // Make an AJAX request to get the fare for the selected trip
                    $.ajax({
                        url: '/trips/getFare/' + tripId, // Pass trip ID as part of the URL
                        type: 'GET',
                        success: function(data) {
                            if (data.fare) {
                                // Populate the amount field with the fare
                                $('#amount').val(data.fare);
                            } else {
                                alert("Error: " + data.error); // Show error if no fare found
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching fare:", error);
                        }
                    });
                } else {
                    $('#amount').val(''); // Clear amount field if no trip selected
                }
            });
        });
    </script>
</x-app-layout>
