@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-fixed bg-cover bg-center pt-24 pb-24 px-4" style="background-image: url('{{ asset('images/sellers.png') }}')">
    <div class="max-w-7xl mx-auto bg-white bg-opacity-75 backdrop-blur-md rounded-xl shadow-xl p-8">

        <!-- Internal Dashboard Navigation -->
        <div class="mb-8 text-sm text-center">
            <nav class="flex flex-wrap justify-center gap-4 text-indigo-700 font-semibold">
                <a href="#top" class="hover:underline">🏠 Dashboard</a>
                <a href="#add-property" class="hover:underline">➕ Add Property</a>
                <a href="#listings" class="hover:underline">📄 My Listings</a>
                <a href="#receipts" class="hover:underline">📥 Download Receipts</a>
                <a href="{{ url('/') }}" class="hover:underline text-red-600">🚪 Logout</a> <!-- Simulated logout -->
            </nav>
        </div>

        <!-- Welcome & Stats -->
        <div id="top" class="mb-10">
            <h1 class="text-3xl font-bold text-black mb-2">Welcome, SellerName</h1>
            <p class="text-gray-600 mb-6">Manage your properties, check stats, and add new listings below.</p>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <p class="text-sm text-gray-600">Total Listings</p>
                    <h2 class="text-2xl font-bold text-indigo-700">12</h2>
                </div>
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <p class="text-sm text-gray-600">Properties Reserved</p>
                    <h2 class="text-2xl font-bold text-green-600">4</h2>
                </div>
                <div class="bg-white rounded-lg shadow p-4 text-center">
                    <p class="text-sm text-gray-600">Total Revenue</p>
                    <h2 class="text-2xl font-bold text-yellow-600">Rs. 6,000,000</h2>
                </div>
            </div>
        </div>

        <!-- Property Listing Form -->
        <div id="add-property" class="mb-12">
            <h2 class="text-2xl font-bold text-black mb-4">List a New Property</h2>
            <div class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded mb-4 text-sm">
            Listing a property requires a fee of Rs. 1,000. (This will be handled during backend phase)
            </div>

            <form method="POST" action="{{ route('properties.store') }}" enctype="multipart/form-data">
                {{-- @csrf --}}

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Title</label>
                        <input type="text" name="title" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="e.g. Spacious 2BR in Nugegoda">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Price (Rs.)</label>
                        <input type="number" name="price" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="e.g. 20000000">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Location</label>
                        <input type="text" name="location" class="w-full border border-gray-300 rounded px-3 py-2" placeholder="e.g. Colombo 05">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Property Type</label>
                        <select name="type" class="w-full border border-gray-300 rounded px-3 py-2">
                            <option value="">-- Select Type --</option>
                            <option value="residential">Residential</option>
                            <option value="commercial">Commercial</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-1">Description</label>
                    <textarea name="description" class="w-full border border-gray-300 rounded px-3 py-2" rows="4" placeholder="Enter detailed description..."></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-1">Upload Property Image</label>
                    <input type="file" name="image" class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>

                <div class="text-right">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition">
                        Submit Listing
                    </button>
                </div>
            </form>
        </div>

        <!-- Listed Properties Table -->
        <div id="listings" class="mb-12">
            <h2 class="text-2xl font-bold text-black mb-4">Your Listed Properties</h2>
            <div class="overflow-x-auto bg-white rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-indigo-50 text-indigo-800 text-sm">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold">Title</th>
                            <th class="px-4 py-2 text-left font-semibold">Price</th>
                            <th class="px-4 py-2 text-left font-semibold">Status</th>
                            <th class="px-4 py-2 text-left font-semibold">Date</th>
                            <th class="px-4 py-2 text-center font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                        @for ($i = 1; $i <= 5; $i++)
                        <tr>
                            <td class="px-4 py-3">Test Property #{{ $i }}</td>
                            <td class="px-4 py-3">Rs. {{ number_format(15 + $i * 2) }}M</td>
                            <td class="px-4 py-3">{{ $i % 2 === 0 ? 'Reserved' : 'Available' }}</td>
                            <td class="px-4 py-3">2025-07-2{{ $i }}</td>
                            <td class="px-4 py-3 text-center space-x-2">
                                <button class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">Edit</button>
                                <button class="bg-red-500 text-white px-3 py-1 rounded text-xs hover:bg-red-600">Delete</button>
                            </td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Receipt Placeholder -->
        <div id="receipts" class="text-center">
            <h2 class="text-xl font-bold text-black mb-2">Download Your Listing Receipts</h2>
            <p class="text-sm text-gray-600 mb-4">These receipts will be available after payment integration.</p>
            <a href="#" class="inline-block bg-gray-300 text-gray-700 px-4 py-2 rounded cursor-not-allowed">Download (coming soon)</a>
        </div>
    </div>
</div>
@endsection
