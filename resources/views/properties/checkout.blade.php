@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-cover bg-center pt-24 pb-24 px-4" style="background-image: url('{{ asset('images/sellers.png') }}')">
    <div class="max-w-3xl mx-auto bg-white bg-opacity-90 backdrop-blur-md rounded-xl shadow-xl p-8">
        <h1 class="text-2xl font-bold text-black mb-6">Confirm Listing & Pay </h1>

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
            <h2 class="font-semibold text-black mb-2">Listing Summary</h2>
            <p><strong>Title:</strong> {{ $draft['title'] }}</p>
            <p><strong>Price:</strong> Rs. {{ number_format($draft['price']) }}</p>
            @if(!empty($draft['location']))<p><strong>Location:</strong> {{ $draft['location'] }}</p>@endif
            @if(!empty($draft['type']))<p><strong>Type:</strong> {{ ucfirst($draft['type']) }}</p>@endif
            @if(!empty($draft['description']))<p><strong>Description:</strong> {{ \Illuminate\Support\Str::limit($draft['description'], 150) }}</p>@endif
            <p class="mt-2"><strong>Listing Fee (mock):</strong> Rs. {{ number_format($fee) }}</p>

            @if(!empty($draft['image_path']))
                <div class="mt-3">
                    <strong class="block mb-1">Image Preview:</strong>
                    <img src="{{ asset('storage/'.$draft['image_path']) }}" alt="Property Preview" class="max-h-48 rounded border">
                </div>
            @endif
        </div>

        <form method="POST" action="{{ route('properties.confirm') }}" class="space-y-4">
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

            @php
                $sellerDash = \Illuminate\Support\Facades\Route::has('dashboard.seller')
                    ? route('dashboard.seller')
                    : (\Illuminate\Support\Facades\Route::has('seller.dashboard')
                        ? route('seller.dashboard')
                        : url('/dashboard/seller'));
            @endphp

            <div class="flex items-center justify-between pt-2">
                <a href="{{ $sellerDash }}" class="text-gray-600 hover:underline">← Cancel</a>
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded px-6 py-2">
                    Pay Rs. {{ number_format($fee) }} & Submit
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
