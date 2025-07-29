@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-cover bg-center flex items-center justify-center pt-24 pb-24" style="background-image: url('{{ asset('images/properties.png') }}')">
    <div class="max-w-4xl w-full mx-auto px-4">
        <div class="bg-white bg-opacity-65 rounded-xl shadow-lg p-8 text-center">
            <h1 class="text-4xl font-extrabold text-black-700 mb-6">Explore Properties</h1>
            <p class="text-lg text-gray-700 mb-10">Browse our collection of commercial and residential properties.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- Residential Section -->
                <a href="{{ url('/properties/residential') }}" class="block bg-indigo-50 border border-indigo-200 rounded-xl p-6 hover:bg-indigo-100 transition">
                    <h2 class="text-2xl font-bold text-indigo-800 mb-2">🏠 Residential Properties</h2>
                    <p class="text-gray-600">Apartments, houses, and villas for comfortable living.</p>
                </a>

                <!-- Commercial Section -->
                <a href="{{ url('/properties/commercial') }}" class="block bg-indigo-50 border border-indigo-200 rounded-xl p-6 hover:bg-indigo-100 transition">
                    <h2 class="text-2xl font-bold text-indigo-800 mb-2">🏢 Commercial Properties</h2>
                    <p class="text-gray-600">Shops, offices, and spaces for business and investments.</p>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
