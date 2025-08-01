@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-cover bg-center flex items-center justify-center pt-24 pb-24 relative overflow-hidden" style="background-image: url('{{ asset('images/properties.png') }}')">
    <div class="absolute inset-0 bg-black bg-opacity-30 z-0"></div>

    <div class="max-w-4xl w-full mx-auto px-4 z-10">
        <div class="bg-white bg-opacity-60 rounded-2xl shadow-2xl p-10 text-center" data-aos="zoom-in">
            <h1 class="text-4xl font-extrabold text-black mb-4" data-aos="fade-up">Explore Properties</h1>
            <p class="text-lg text-gray-700 mb-10" data-aos="fade-up" data-aos-delay="100">
                Browse our collection of commercial and residential properties.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10" data-aos="fade-up" data-aos-delay="200">
                <!-- Residential Section -->
                <a href="{{ url('/properties/residential') }}"
                   class="group block bg-white bg-opacity-80 rounded-xl shadow-md p-6 hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <h2 class="text-2xl font-bold text-indigo-800 mb-2 group-hover:text-indigo-600 transition">Residential Properties</h2>
                    <p class="text-gray-600">Apartments, houses, and villas for comfortable living.</p>
                </a>

                <!-- Commercial Section -->
                <a href="{{ url('/properties/commercial') }}"
                   class="group block bg-white bg-opacity-80 rounded-xl shadow-md p-6 hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <h2 class="text-2xl font-bold text-indigo-800 mb-2 group-hover:text-indigo-600 transition">Commercial Properties</h2>
                    <p class="text-gray-600">Shops, offices, and spaces for business and investments.</p>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
