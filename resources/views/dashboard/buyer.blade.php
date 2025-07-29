@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-cover bg-center pt-24 pb-24 px-4" style="background-image: url('{{ asset('images/sellers.png') }}')">
    <div class="max-w-7xl mx-auto bg-white bg-opacity-90 backdrop-blur-md rounded-xl shadow-xl p-8">

        <!-- Internal Navigation -->
        <div class="mb-8 text-sm text-center">
            <nav class="flex flex-wrap justify-center gap-4 text-indigo-700 font-semibold">
                <a href="#top" class="hover:underline">🏠 Dashboard</a>
                <a href="#reservations" class="hover:underline">📋 My Reservations</a>
                <a href="#receipts" class="hover:underline">📥 My Receipts</a>
                <a href="{{ url('/properties') }}" class="hover:underline">🔍 Browse Properties</a>
                <a href="{{ url('/reserve') }}" class="hover:underline">💳 Reserve Property</a>
                <a href="{{ url('/') }}" class="hover:underline text-red-600">🚪 Logout</a>
            </nav>
        </div>

        <!-- Welcome + Stats -->
        <div id="top" class="mb-10">
            <h1 class="text-3xl font-bold text-black mb-2">Welcome, buyerName</h1>
            <p class="text-gray-600 mb-6">Track your reservations, view receipts, and explore more properties.</p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <p class="text-sm text-gray-600">Total Reservations</p>
                    <h2 class="text-2xl font-bold text-indigo-700">3</h2>
                </div>
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <p class="text-sm text-gray-600">Total Paid</p>
                    <h2 class="text-2xl font-bold text-green-600">Rs. 75,000</h2>
                </div>
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <p class="text-sm text-gray-600">Favorite Listings</p>
                    <h2 class="text-2xl font-bold text-yellow-600">5</h2>
                </div>
            </div>
        </div>

        <!-- Reserved Properties Table -->
        <div id="reservations" class="mb-12">
            <h2 class="text-2xl font-bold text-black mb-4">My Reservations</h2>

            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-indigo-50 text-indigo-800 text-sm">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold">Property</th>
                            <th class="px-4 py-2 text-left font-semibold">Price</th>
                            <th class="px-4 py-2 text-left font-semibold">Status</th>
                            <th class="px-4 py-2 text-left font-semibold">Reserved On</th>
                            <th class="px-4 py-2 text-center font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                        @for ($i = 1; $i <= 3; $i++)
                        <tr>
                            <td class="px-4 py-3">Property #{{ $i }}</td>
                            <td class="px-4 py-3">Rs. {{ number_format(20000000 + $i * 1000000) }}</td>
                            <td class="px-4 py-3">{{ $i % 2 == 0 ? 'Pending' : 'Confirmed' }}</td>
                            <td class="px-4 py-3">2025-07-2{{ $i }}</td>
                            <td class="px-4 py-3 text-center space-x-2">
                                <button class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">View</button>
                                <button class="bg-indigo-600 text-white px-3 py-1 rounded text-xs hover:bg-indigo-700">Download Receipt</button>
                            </td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Receipts Placeholder -->
        <div id="receipts" class="mb-12 text-center">
            <h2 class="text-xl font-bold text-black mb-2">My Receipts</h2>
            <p class="text-sm text-gray-600 mb-4">Download your payment receipts for reserved properties here.</p>
            <a href="#" class="inline-block bg-gray-300 text-gray-700 px-4 py-2 rounded cursor-not-allowed">
                Download (coming soon)
            </a>
        </div>

        <!-- Browse Properties Shortcut -->
        <div class="text-center">
            <h2 class="text-xl font-bold text-black mb-2">Want to reserve more?</h2>
            <a href="{{ url('/properties') }}" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition">
                🔍 Browse Properties
            </a>
        </div>
    </div>
</div>
@endsection
