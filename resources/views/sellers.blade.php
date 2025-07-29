@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-cover bg-center pt-24 pb-24 px-4" style="background-image: url('{{ asset('images/sellers.png') }}')">
    <div class="max-w-7xl mx-auto text-center bg-white bg-opacity-65 rounded-xl p-8 shadow-lg">
        <h1 class="text-4xl font-bold text-black-700 mb-4">Meet Our Trusted Sellers</h1>
        <p class="text-lg text-gray-600 mb-10">These professionals have consistently delivered quality service and exceptional results in property management and sales.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @php
                $sellers = [
                    ['name' => 'Michael Scofield', 'image' => 'seller1.jpg'],
                    ['name' => 'Edward Cullen', 'image' => 'seller2.jpg'],
                    ['name' => 'Bellamy Blake', 'image' => 'seller3.jpg'],
                    ['name' => 'Ryohei Arisu', 'image' => 'seller4.jpg'],
                    ['name' => 'Merlin Ambrosius', 'image' => 'seller5.jpg'],
                    ['name' => 'Hermione Granger', 'image' => 'seller6.jpg'],
                    ['name' => 'Wei Wuxian', 'image' => 'seller7.jpg'],
                    ['name' => 'Lan Wangji', 'image' => 'seller8.jpg'],
                    ['name' => 'Clarke Griffin', 'image' => 'seller9.jpg'],
                    ['name' => 'Enola Holmes', 'image' => 'seller10.jpg'],
                ];
            @endphp

            @foreach ($sellers as $index => $seller)
                <div class="bg-white bg-opacity-95 rounded-lg shadow-md hover:shadow-xl overflow-hidden text-left">
                    <div class="w-full h-40 bg-white p-2 flex items-center justify-center">
                        <img src="{{ asset('images/' . $seller['image']) }}" alt="{{ $seller['name'] }}" class="max-h-full object-contain">
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-semibold text-indigo-700 mb-1">{{ $seller['name'] }}</h3>
                        <p class="text-gray-600 text-sm mb-2">📞 07{{ rand(0,9) }}{{ rand(10000000,99999999) }}</p>
                        <p class="text-gray-600 text-sm mb-2">🏅 Rank: {{ ['Bronze', 'Silver', 'Gold', 'Platinum'][rand(0,3)] }} Seller</p>
                        <p class="text-gray-600 text-sm mb-4">💰 Sold Worth: Rs. {{ number_format(rand(2, 30) * 1000000) }}</p>
                        <a href="#" class="inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 text-sm">View Listings</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
