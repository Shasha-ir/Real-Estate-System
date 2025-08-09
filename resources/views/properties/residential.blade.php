@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 pt-24 pb-24 px-4">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-black-700 text-center mb-10">Residential Properties</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            @for ($i = 1; $i <= 6; $i++)
                <div class="bg-white rounded-lg shadow hover:shadow-xl overflow-hidden cursor-pointer transition transform hover:scale-105 duration-300" onclick="showModal({{ $i }})">
                    <img src="{{ asset('images/image' . $i . '.jpg') }}" alt="Property {{ $i }}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h2 class="text-lg font-semibold text-gray-800">Property #{{ $i }}</h2>
                        <p class="text-sm text-gray-500">Click to view more details</p>
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <!-- Modal -->
    <div id="propertyModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white w-full max-w-2xl rounded-lg overflow-hidden shadow-lg">
            <div class="relative">
                <img id="modalImage" src="" class="w-full h-64 object-cover">
                <button onclick="closeModal()" class="absolute top-2 right-2 bg-white rounded-full p-2 shadow text-gray-600 hover:text-red-600">&times;</button>
            </div>
            <div class="p-6">
                <h2 class="text-2xl font-bold text-indigo-700 mb-2" id="modalTitle">Property Title</h2>
                <p class="text-gray-600 mb-1"><strong>Price:</strong> Rs. 1,500,000</p>
                <p class="text-gray-600 mb-1"><strong>Seller:</strong> Test Seller</p>
                <p class="text-gray-600 mb-1"><strong>Location:</strong> Colombo 07</p>
                <p class="text-gray-600 mb-4"><strong>Highlights:</strong> 3 Bedrooms, 2 Bathrooms, Garden, Parking</p>

                @auth
                    @if (auth()->user()->role->name === 'buyer')
                        <a href="{{ route('reservation.form') }}" class="block w-full text-center bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700 transition">
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
        function showModal(id) {
            document.getElementById('modalImage').src = `/images/image${id}.jpg`;
            document.getElementById('modalTitle').innerText = `Property #${id}`;
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
