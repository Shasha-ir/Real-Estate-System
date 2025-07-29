@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-16 bg-gray-100">
    <div class="max-w-xl w-full bg-white rounded-xl shadow-lg p-8">

        <h1 class="text-3xl font-bold text-black mb-4 text-center">Reserve a Property</h1>
        <p class="text-gray-600 text-center mb-6">Enter the Property ID and your details to simulate reservation payment.</p>

        <form id="paymentForm" action="{{ route('reservation.process') }}" method="POST" class="mb-40">
            {{-- @csrf --}}

            <!-- STEP 1: Property ID -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Property ID</label>
                <input type="number" name="property_id" id="propertyIdInput" class="w-full border rounded px-3 py-2 mt-1" placeholder="Enter Property ID (e.g. 1)" required>
            </div>

            <!-- Property Info -->
            <div id="propertyInfo" class="bg-indigo-50 border border-indigo-200 rounded-lg p-4 mb-6 hidden">
                <h2 class="text-lg font-semibold text-indigo-700 mb-1" id="propTitle">🏠 Property Title</h2>
                <p class="text-sm text-gray-700" id="propLocation">📍 Location</p>
                <p class="text-sm text-gray-700" id="propPrice">💰 Price</p>
                <p class="text-sm text-gray-700" id="propFee">🧾 Reservation Fee</p>
            </div>

            <input type="hidden" name="amount" id="formFee" value="">

            <!-- STEP 2: User Details -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                <input type="text" name="full_name" class="w-full border rounded px-3 py-2 mt-1" placeholder="e.g. Hermione Granger" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Card Number</label>
                <input type="text" name="card_number" class="w-full border rounded px-3 py-2 mt-1" placeholder="e.g. 1234 5678 9012 3456" required>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Expiry</label>
                    <input type="text" name="expiry" class="w-full border rounded px-3 py-2 mt-1" placeholder="MM/YY" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">CVC</label>
                    <input type="text" name="cvc" class="w-full border rounded px-3 py-2 mt-1" placeholder="e.g. 123" required>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700">Note (optional)</label>
                <textarea name="note" class="w-full border rounded px-3 py-2 mt-1" rows="3" placeholder="Leave a message..."></textarea>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition">
                Reserve & Pay
            </button>
        </form>

        <!-- Placeholder for confirmation -->
        <div class="text-center text-sm text-gray-500 italic">
            You will receive a receipt and dashboard access after full integration.
        </div>
    </div>
</div>

<!-- JavaScript for dynamic property details -->
<script>
    const mockProperties = {
        1: { title: '2BR Apartment', price: 20000000, fee: 25000, location: 'Colombo 07' },
        2: { title: 'Office Space', price: 35000000, fee: 30000, location: 'Kandy' },
        3: { title: 'Luxury Villa', price: 60000000, fee: 50000, location: 'Galle' },
    };

    const idInput = document.getElementById('propertyIdInput');
    const infoBox = document.getElementById('propertyInfo');
    const titleEl = document.getElementById('propTitle');
    const locEl = document.getElementById('propLocation');
    const priceEl = document.getElementById('propPrice');
    const feeEl = document.getElementById('propFee');
    const feeInput = document.getElementById('formFee');

    idInput.addEventListener('input', () => {
        const id = idInput.value;
        const prop = mockProperties[id];

        if (prop) {
            infoBox.classList.remove('hidden');
            titleEl.innerText = `🏠 ${prop.title}`;
            locEl.innerText = `📍 Location: ${prop.location}`;
            priceEl.innerText = `💰 Price: Rs. ${prop.price.toLocaleString()}`;
            feeEl.innerText = `🧾 Reservation Fee: Rs. ${prop.fee.toLocaleString()}`;
            feeInput.value = prop.fee;
        } else {
            infoBox.classList.add('hidden');
            feeInput.value = '';
        }
    });
</script>
@endsection
