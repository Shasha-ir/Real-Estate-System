@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-cover bg-center pt-24 pb-24 px-4"
        style="background-image: url('{{ asset('images/sellers.png') }}')">
        <div class="max-w-7xl mx-auto text-center bg-white bg-opacity-70 rounded-xl p-10 shadow-xl backdrop-blur-md">
            <h1 class="text-4xl font-bold text-black mb-4" data-aos="fade-down">Meet Our Trusted Sellers</h1>
            <p class="text-lg text-gray-700 mb-10" data-aos="fade-up" data-aos-delay="100">
                These professionals have consistently delivered quality service and exceptional results in property
                management and sales.
            </p>

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
                    <div class="bg-white bg-opacity-95 rounded-lg shadow-md overflow-hidden text-left transition-all duration-300"
                        data-aos="zoom-in" data-aos-delay="{{ $index * 100 }}">
                        <div class="w-full h-40 bg-white p-2 flex items-center justify-center">
                            <img src="{{ asset('images/' . $seller['image']) }}" alt="{{ $seller['name'] }}"
                                class="max-h-full object-contain">
                        </div>
                        <div class="p-4">
                            <h3 class="text-xl font-semibold text-indigo-700 mb-1">{{ $seller['name'] }}</h3>
                            <p class="text-gray-600 text-sm mb-2">📞 07{{ rand(0, 9) }}{{ rand(10000000, 99999999) }}</p>
                            <p class="text-gray-600 text-sm mb-2">🏅 Rank:
                                {{ ['Bronze', 'Silver', 'Gold', 'Platinum'][rand(0, 3)] }} Seller
                            </p>
                            <p class="text-gray-600 text-sm mb-4">💰 Sold Worth: Rs. {{ number_format(rand(2, 30) * 1000000) }}
                            </p>
                            @auth
                                @if(in_array(auth()->user()->role->name, ['buyer', 'seller']))
                                    <a href="#"
                                        class="inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 text-sm transition">
                                        View Listings
                                    </a>
                                @else
                                    <button onclick="showRestrictedPopup()"
                                        class="inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 text-sm transition">
                                        View Listings
                                    </button>
                                @endif
                            @else
                                <button onclick="showRestrictedPopup()"
                                    class="inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 text-sm transition">
                                    View Listings
                                </button>
                            @endauth

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Popup Modal -->
    <div id="restrictedPopup" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 relative">
            <h2 class="text-xl font-semibold  mb-2">Login Required</h2>
            <p class="text-gray-700 mb-4">
                Only logged-in <strong>buyers or sellers</strong> can view property listings.
                Please log in to continue.
            </p>
            <div class="flex justify-end space-x-2">
                <a href="{{ route('login') }}"
                    class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 text-sm">Login</a>
                <button onclick="closeRestrictedPopup()"
                    class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 text-sm">Cancel</button>
            </div>
            <button onclick="closeRestrictedPopup()"
                class="absolute top-2 right-3 text-gray-500 hover:text-red-500 text-xl">&times;</button>
        </div>
    </div>

    <script>
        function showRestrictedPopup() {
            document.getElementById('restrictedPopup').classList.remove('hidden');
            document.getElementById('restrictedPopup').classList.add('flex');
        }

        function closeRestrictedPopup() {
            document.getElementById('restrictedPopup').classList.add('hidden');
            document.getElementById('restrictedPopup').classList.remove('flex');
        }
    </script>

@endsection