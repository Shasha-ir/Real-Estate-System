@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 pt-24 pb-24 px-4">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-black-700 text-center mb-10">Commercial Properties</h1>

        {{-- Use DB data if present; otherwise fall back to your static 7..12 demo cards --}}
        @php $hasData = isset($properties) && count($properties) > 0; @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            @if($hasData)
                @foreach ($properties as $p)
                    @php
                        // keep using your images; just cycle 7..12
                        $imgIndex = 7 + ($loop->index % 6);
                        $imgUrl   = asset('images/image' . $imgIndex . '.jpg');
                    @endphp
                    <div class="bg-white rounded-lg shadow hover:shadow-xl overflow-hidden cursor-pointer transition transform hover:scale-105 duration-300"
                         onclick="showModal(this)"
                         data-image="{{ $imgUrl }}"
                         data-title="{{ $p->title }}"
                         data-price="{{ number_format($p->price) }}"
                         data-seller="{{ $p->seller_username ?? 'Urban Developer Ltd.' }}"
                         data-location="{{ $p->location }}"
                         data-reserve-url="{{ route('reservation.form', ['property' => $p->id]) }}">
                        <img src="{{ $imgUrl }}" alt="Property {{ $loop->iteration }}" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h2 class="text-lg font-semibold text-gray-800">{{ $p->title }}</h2>
                            <p class="text-sm text-gray-500">Click to view more details</p>
                        </div>
                    </div>
                @endforeach
            @else
                @for ($i = 7; $i <= 12; $i++)
                    <div class="bg-white rounded-lg shadow hover:shadow-xl overflow-hidden cursor-pointer transition transform hover:scale-105 duration-300"
                         onclick="showModal(this)"
                         data-image="{{ asset('images/image' . $i . '.jpg') }}"
                         data-title="Commercial Unit #{{ $i }}"
                         data-price="{{ number_format(3800000) }}"
                         data-seller="Urban Developer Ltd."
                         data-location="Colombo 03"
                         data-reserve-url="">
                        <img src="{{ asset('images/image' . $i . '.jpg') }}" alt="Property {{ $i }}" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h2 class="text-lg font-semibold text-gray-800">Commercial Unit #{{ $i }}</h2>
                            <p class="text-sm text-gray-500">Click to view more details</p>
                        </div>
                    </div>
                @endfor
            @endif
        </div>
    </div>

    <!-- Modal -->
    <div id="propertyModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white w-full max-w-2xl rounded-lg overflow-hidden shadow-lg">
            <div class="relative">
                <img id="modalImage" src="" class="w-full h-64 object-cover" alt="">
                <button onclick="closeModal()" class="absolute top-2 right-2 bg-white rounded-full p-2 shadow text-gray-600 hover:text-red-600">&times;</button>
            </div>
            <div class="p-6">
                <h2 class="text-2xl font-bold text-black-700 mb-2" id="modalTitle">Commercial Property</h2>
                <p class="text-gray-600 mb-1"><strong>Price:</strong> <span id="modalPrice">Rs. 3,800,000</span></p>
                <p class="text-gray-600 mb-1"><strong>Seller:</strong> <span id="modalSeller">Urban Developer Ltd.</span></p>
                <p class="text-gray-600 mb-1"><strong>Location:</strong> <span id="modalLocation">Colombo 03</span></p>
                <p class="text-gray-600 mb-4"><strong>Highlights:</strong> 1000 sqft, Road-facing, Air Conditioning, Parking</p>

                @auth
                    @if (auth()->user()->role->name === 'buyer')
                        {{-- href set dynamically from the clicked card --}}
                        <a id="reserveLink" href="#"
                           class="block w-full text-center bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700 transition">
                            Reserve Property
                        </a>
                    @else
                        <button onclick="showAuthAlert()" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">
                            Reserve Property
                        </button>
                    @endif
                @else
                    <button onclick="showAuthAlert()" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700">
                        Reserve Property
                    </button>
                @endauth
            </div>
        </div>
    </div>

    <!-- Auth Alert -->
    <div id="authAlert" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full text-center">
            <h3 class="text-xl font-bold text-gray-800 mb-4">Login Required</h3>
            <p class="text-sm text-gray-600 mb-6">Only buyers can reserve properties. Please log in as a buyer to continue.</p>
            <button onclick="closeAuthAlert()" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">OK</button>
        </div>
    </div>

    <script>
        function showModal(el) {
            var img        = el.dataset.image || '';
            var title      = el.dataset.title || 'Commercial Property';
            var price      = el.dataset.price ? 'Rs. ' + el.dataset.price : 'Rs. —';
            var seller     = el.dataset.seller || '—';
            var locationTx = el.dataset.location || '—';
            var reserveUrl = el.dataset.reserveUrl || '';

            document.getElementById('modalImage').src      = img;
            document.getElementById('modalTitle').innerText= title;
            document.getElementById('modalPrice').innerText= price;
            document.getElementById('modalSeller').innerText= seller;
            document.getElementById('modalLocation').innerText= locationTx;

            var reserveLink = document.getElementById('reserveLink');
            if (reserveLink) {
                if (reserveUrl) {
                    reserveLink.setAttribute('href', reserveUrl);
                    reserveLink.classList.remove('cursor-not-allowed','opacity-60');
                } else {
                    reserveLink.setAttribute('href', '#');
                    reserveLink.classList.add('cursor-not-allowed','opacity-60');
                }
            }

            document.getElementById('propertyModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('propertyModal').classList.add('hidden');
        }

        function showAuthAlert() {
            document.getElementById('authAlert').classList.remove('hidden');
        }

        function closeAuthAlert() {
            document.getElementById('authAlert').classList.add('hidden');
        }
    </script>
</div>
@endsection
