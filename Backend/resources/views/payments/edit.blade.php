<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Payment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- Success and error messages --}}
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

                    {{-- Payment Edit Form --}}
                    <form action="{{ route('payments.update', $payment->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Trip Information (Display-Only) --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Trip') }}</label>
                            <p class="mt-1 block w-full bg-gray-100 dark:bg-gray-700 dark:text-gray-300 rounded-md px-4 py-2">
                                {{ $payment->trip->id }} - {{ $payment->trip->pickup_location }} to {{ $payment->trip->dropoff_location }}
                            </p>
                        </div>

                        {{-- Hidden Trip ID Field --}}
                        <input type="hidden" name="trip_id" value="{{ $payment->trip->id }}">

                        {{-- Amount Field (Read-Only) --}}
                        <div class="mb-4">
                            <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Amount (Fare)') }}</label>
                            <input type="text" name="amount" id="amount" value="{{ $payment->amount }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" readonly>
                        </div>

                        {{-- Payment Method (Read-Only) --}}
                        <div class="mb-4">
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Payment Method') }}</label>
                            <select name="payment_method" id="payment_method" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300">
                                <option value="CASH" {{ $payment->payment_method == 'CASH' ? 'selected' : '' }}>{{ __('Cash') }}</option>
                                <option value="CREDIT_CARD" {{ $payment->payment_method == 'CREDIT_CARD' ? 'selected' : '' }}>{{ __('Credit Card') }}</option>
                                <option value="DEBIT_CARD" {{ $payment->payment_method == 'DEBIT_CARD' ? 'selected' : '' }}>{{ __('Debit Card') }}</option>
                                <option value="ONLINE" {{ $payment->payment_method == 'ONLINE' ? 'selected' : '' }}>{{ __('Online') }}</option>
                            </select>
                            @error('payment_method')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Payment Status (Editable) --}}
                        <div class="mb-4">
                            <label for="payment_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Payment Status') }}</label>
                            <select name="payment_status" id="payment_status" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                                <option value="PENDING" {{ $payment->payment_status == 'PENDING' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                <option value="PAID" {{ $payment->payment_status == 'PAID' ? 'selected' : '' }}>{{ __('Paid') }}</option>
                                <option value="FAILED" {{ $payment->payment_status == 'FAILED' ? 'selected' : '' }}>{{ __('Failed') }}</option>
                            </select>
                            @error('payment_status')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Submit Button --}}
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-700">{{ __('Update Payment') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
