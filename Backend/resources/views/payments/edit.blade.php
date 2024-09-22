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
                    <!-- Edit Payment Form -->
                    <form action="{{ route('payments.update', $payment->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="booking_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Booking Id') }}</label>
                            <input type="text" name="booking_id" id="booking_id" value="{{ old('booking_id', $payment->booking_id) }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('booking_id')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Amount') }}</label>
                            <input type="text" name="amount" id="amount" value="{{ old('amount', $payment->amount) }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('amount')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Payment Method') }}</label>
                            <input type="text" name="payment_method" id="payment_method" value="{{ old('payment_method', $payment->payment_method) }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('payment_method')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="payment_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Payment Status') }}</label>
                            <input type="text" name="payment_status" id="payment_status" value="{{ old('payment_status', $passenger->payment_status) }}" class="mt-1 block w-full rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @error('payment_status')
                                <span class="text-sm text-red-600">{{ $message }}</span>
                            @enderror
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
