@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 pt-24 pb-24 px-4">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-black-700 text-center mb-10">Commercial Properties</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            @for ($i = 7; $i <= 12; $i++)
                <div class="bg-white rounded-lg shadow hover:shadow-xl overflow-hidden cursor-pointer" onclick="showModal({{ $i }})">
                    <img src="{{ asset('images/image' . $i . '.jpg') }}" alt="Property {{ $i }}" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h2 class="text-lg font-semibold text-gray-800">Commercial Unit #{{ $i }}</h2>
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
                <h2 class="text-2xl font-bold text-black-700 mb-2" id="modalTitle">Commercial Property</h2>
                <p class="text-gray-600 mb-1"><strong>Price:</strong> Rs. 3,800,000</p>
                <p class="text-gray-600 mb-1"><strong>Seller:</strong> Urban Developer Ltd.</p>
                <p class="text-gray-600 mb-1"><strong>Location:</strong> Colombo 03</p>
                <p class="text-gray-600 mb-4"><strong>Highlights:</strong> 1000 sqft, Road-facing, Air Conditioning, Parking</p>

                {{-- Simulate login behavior --}}
                <a href="/reserve" class="block w-full text-center bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700 transition">
                    Reserve Property
                </a>
            </div>
        </div>
    </div>

    <script>
        function showModal(id) {
            const modal = document.getElementById('propertyModal');
            const img = document.getElementById('modalImage');
            const title = document.getElementById('modalTitle');

            img.src = `/images/image${id}.jpg`;
            title.innerText = `Commercial Unit #${id}`;
            modal.classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('propertyModal').classList.add('hidden');
        }
    </script>
</div>
@endsection
