@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-cover bg-center pt-24 pb-24 px-4" style="background-image: url('{{ asset('images/sellers.png') }}')">
    <div class="max-w-3xl mx-auto bg-white bg-opacity-90 backdrop-blur-md rounded-xl shadow-xl p-8">
        <h1 class="text-2xl font-bold text-black mb-6">Reserve Property</h1>

        @if ($errors->any())
            <div class="mb-4 rounded bg-red-100 text-red-800 px-4 py-3 text-sm">
                <ul class="list-disc ml-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-6 bg-white rounded border p-4">
            <h2 class="font-semibold text-black mb-2">Summary</h2>
            <p><strong>Property:</strong> {{ $property->title }}</p>
            <p><strong>Location:</strong> {{ $property->location }}</p>
            <p><strong>Price:</strong> Rs. {{ number_format($property->price) }}</p>
            <p class="mt-2"><strong>Reservation Fee (mock):</strong> Rs. {{ number_format($fee) }}</p>
        </div>

        {{-- IMPORTANT: include the property id in the action --}}
        <form method="POST" action="{{ route('reservation.process', ['property' => $property->id]) }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Cardholder Name</label>
                <input name="card_name" type="text" class="w-full border rounded px-3 py-2" required value="{{ old('card_name') }}">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Card Number</label>
                <input name="card_number" inputmode="numeric" class="w-full border rounded px-3 py-2" placeholder="4111 1111 1111 1111" required value="{{ old('card_number') }}">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Expiry (MM/YY)</label>
                    <input name="expiry" class="w-full border rounded px-3 py-2" placeholder="09/28" required value="{{ old('expiry') }}">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">CVC</label>
                    <input name="cvc" inputmode="numeric" class="w-full border rounded px-3 py-2" placeholder="123" required value="{{ old('cvc') }}">
                </div>
            </div>

            <div class="flex items-center justify-between pt-2">
                <a href="{{ route('properties.index') }}" class="text-gray-600 hover:underline">← Cancel</a>
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded px-6 py-2">
                    Pay Rs. {{ number_format($fee) }} & Reserve
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
