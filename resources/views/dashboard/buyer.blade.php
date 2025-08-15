@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-cover bg-center pt-24 pb-24 px-4"
        style="background-image: url('{{ asset('images/sellers.png') }}')">
        <div class="max-w-7xl mx-auto bg-white bg-opacity-90 backdrop-blur-md rounded-xl shadow-xl p-8">
            @include('partials.profile-settings')
            @php
                $u = auth()->user();
                $emailPrefix = $u && $u->email ? explode('@', $u->email)[0] : '';

                // If the controller didn't pass these yet, fall back to safe defaults
                $stats = $stats ?? ['total_reservations' => 0, 'total_paid' => 0, 'favorites_count' => 0];
                $reservations = $reservations ?? collect();
            @endphp


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
                <h1 class="text-3xl font-bold text-black mb-2">
                    Welcome, {{ $u->username ?? $u->name ?? $emailPrefix }}
                </h1>

                <p class="text-gray-600 mb-6">Track your reservations, view receipts, and explore more properties.</p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-white rounded-lg shadow p-4 text-center">
                        <p class="text-sm text-gray-600">Total Reservations</p>
                        <h2 class="text-2xl font-bold text-indigo-700">
                            {{ number_format($stats['total_reservations'] ?? 0) }}
                        </h2>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4 text-center">
                        <p class="text-sm text-gray-600">Total Paid</p>
                        <h2 class="text-2xl font-bold text-green-600">
                            Rs. {{ number_format($stats['total_paid'] ?? 0) }}
                        </h2>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4 text-center">
                        <p class="text-sm text-gray-600">Favorite Listings</p>
                        <h2 class="text-2xl font-bold text-yellow-600">{{ number_format($stats['favorites_count'] ?? 0) }}
                        </h2>
                    </div>
                </div>
            </div>

            <!-- My Reservations -->
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
                @forelse(($myReservations ?? collect()) as $r)
                    @php
                        $date = $r->reserved_at ? \Carbon\Carbon::parse($r->reserved_at)->format('Y-m-d H:i') : '—';
                    @endphp
                    <tr>
                        <td class="px-4 py-3">{{ $r->title }}</td>
                        <td class="px-4 py-3">Rs. {{ number_format((float)($r->price ?? 0)) }}</td>
                        <td class="px-4 py-3">{{ ucfirst($r->status) }}</td>
                        <td class="px-4 py-3">{{ $date }}</td>
                        <td class="px-4 py-3 text-center space-x-2">
                            <button
                                type="button"
                                class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600"
                                onclick="openReservationModal(this)"
                                data-title="{{ $r->title }}"
                                data-location="{{ $r->location }}"
                                data-price="Rs. {{ number_format((float)($r->price ?? 0)) }}"
                                data-fee="Rs. {{ number_format((float)($r->fee ?? 0)) }}"
                                data-status="{{ ucfirst($r->status) }}"
                                data-date="{{ $date }}"
                                data-seller="{{ $r->seller_username }}"
                                data-phone="{{ $r->phone ?? '' }}"
                                data-email="{{ $r->seller_email }}">
                                View
                            </button>

                            {{-- Keep your receipt button as-is (stub or working) --}}
                            <button class="bg-indigo-600 text-white px-3 py-1 rounded text-xs hover:bg-indigo-700">
                                Download Receipt
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                            You have no reservations yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Reservation Detail Modal --}}
<div id="reservationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white w-full max-w-lg rounded-lg overflow-hidden shadow-xl">
        <div class="px-6 py-4 border-b flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">Reservation Details</h3>
            <button onclick="closeReservationModal()" class="text-gray-500 hover:text-red-600 text-2xl leading-none">&times;</button>
        </div>

        <div class="px-6 py-5 space-y-2 text-sm text-gray-700">
            <p><span class="font-semibold text-gray-900">Property:</span> <span id="rmTitle">—</span></p>
            <p><span class="font-semibold text-gray-900">Location:</span> <span id="rmLocation">—</span></p>
            <p><span class="font-semibold text-gray-900">Price:</span> <span id="rmPrice">—</span></p>
            <p><span class="font-semibold text-gray-900">Reservation Fee:</span> <span id="rmFee">—</span></p>
            <p><span class="font-semibold text-gray-900">Status:</span> <span id="rmStatus">—</span></p>
            <p><span class="font-semibold text-gray-900">Reserved On:</span> <span id="rmDate">—</span></p>
            <hr class="my-3">
            <p><span class="font-semibold text-gray-900">Seller:</span> <span id="rmSeller">—</span></p>
            <p>
                <span class="font-semibold text-gray-900">Phone:</span>
                <span id="rmPhone" class="inline-block">—</span>
            </p>
            <p>
                <span class="font-semibold text-gray-900">Email:</span>
                <span id="rmEmail">—</span>
            </p>

            <div class="mt-4 flex gap-2">
                <a id="rmCallBtn" href="#" class="px-4 py-2 rounded bg-green-600 text-white text-sm hover:bg-green-700">Call Seller</a>
                <a id="rmEmailBtn" href="#" class="px-4 py-2 rounded bg-indigo-600 text-white text-sm hover:bg-indigo-700">Email Seller</a>
            </div>

            <p class="text-xs text-gray-500 mt-2">Tip: contact the seller to fix a date to visit the property.</p>
        </div>

        <div class="px-6 py-4 border-t text-right">
            <button onclick="closeReservationModal()" class="px-4 py-2 rounded bg-gray-200 text-gray-800 hover:bg-gray-300">Close</button>
        </div>
    </div>
</div>

<script>
function openReservationModal(btn) {
    const d = btn.dataset;

    document.getElementById('rmTitle').textContent    = d.title || '—';
    document.getElementById('rmLocation').textContent = d.location || '—';
    document.getElementById('rmPrice').textContent    = d.price || '—';
    document.getElementById('rmFee').textContent      = d.fee || '—';
    document.getElementById('rmStatus').textContent   = d.status || '—';
    document.getElementById('rmDate').textContent     = d.date || '—';
    document.getElementById('rmSeller').textContent   = d.seller || '—';
    document.getElementById('rmEmail').textContent    = d.email || '—';

    const phoneEl = document.getElementById('rmPhone');
    const callBtn = document.getElementById('rmCallBtn');
    const emailBtn= document.getElementById('rmEmailBtn');

    if (d.phone && d.phone.trim() !== '') {
        phoneEl.textContent = d.phone;
        callBtn.href = 'tel:' + d.phone.replace(/\s+/g, '');
        callBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    } else {
        phoneEl.textContent = 'Not provided';
        callBtn.href = '#';
        callBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }

    if (d.email && d.email.trim() !== '') {
        emailBtn.href = 'mailto:' + d.email;
    } else {
        emailBtn.href = '#';
    }

    const modal = document.getElementById('reservationModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeReservationModal() {
    const modal = document.getElementById('reservationModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>


            <!-- Receipts -->
            <div id="receipts" class="mb-12 text-center">
                <h2 class="text-xl font-bold text-black mb-2">My Receipts</h2>
                @if(\Illuminate\Support\Facades\Route::has('reservations.receipt') && $reservations->count())
                    <p class="text-sm text-gray-600 mb-4">Download your payment receipts for reserved properties.</p>
                    <div class="flex flex-wrap justify-center gap-2">
                        @foreach ($reservations as $r)
                            <a href="{{ route('reservations.receipt', $r->id) }}"
                                class="inline-block bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition">
                                Receipt #{{ $r->id }}
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-600 mb-4">Download your payment receipts for reserved properties here.</p>
                    <span class="inline-block bg-gray-300 text-gray-700 px-4 py-2 rounded cursor-not-allowed">
                        Download (coming soon)
                    </span>
                @endif
            </div>

            <!-- Browse Properties Shortcut -->
            <div class="text-center">
                <h2 class="text-xl font-bold text-black mb-2">Want to reserve more?</h2>
                <a href="{{ url('/properties') }}"
                    class="inline-block bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition">
                    🔍 Browse Properties
                </a>
            </div>
        </div>
    </div>
@endsection