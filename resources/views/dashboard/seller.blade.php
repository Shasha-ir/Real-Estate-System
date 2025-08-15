@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-fixed bg-cover bg-center pt-24 pb-24 px-4"
        style="background-image: url('{{ asset('images/sellers.png') }}')">
        <div class="max-w-7xl mx-auto bg-white bg-opacity-75 backdrop-blur-md rounded-xl shadow-xl p-8">
            @include('partials.profile-settings')

            @php
                $u = auth()->user();
                $emailPrefix = $u && $u->email ? explode('@', $u->email)[0] : '';
                $stats = $stats ?? ['total_listings' => 0, 'properties_reserved' => 0, 'total_revenue' => 0];
                $listings = $listings ?? collect();

                $hasStore = \Illuminate\Support\Facades\Route::has('properties.store');
                $hasShow = \Illuminate\Support\Facades\Route::has('properties.show');
                $hasEdit = \Illuminate\Support\Facades\Route::has('properties.edit');
                $hasDestroy = \Illuminate\Support\Facades\Route::has('properties.destroy');
            @endphp

            <!-- Internal Dashboard Navigation -->
            <div class="mb-8 text-sm text-center">
                <nav class="flex flex-wrap justify-center gap-4 text-indigo-700 font-semibold">
                    <a href="#top" class="hover:underline">🏠 Dashboard</a>
                    <a href="#add-property" class="hover:underline">➕ Add Property</a>
                    <a href="#listings" class="hover:underline">📄 My Listings</a>
                    <a href="#receipts" class="hover:underline">📥 Download Receipts</a>
                    <a href="{{ url('/') }}" class="hover:underline text-red-600">🚪 Logout</a>
                </nav>
            </div>

            <!-- Welcome & Stats -->
            <div id="top" class="mb-10">
                <h1 class="text-3xl font-bold text-black mb-2">
                    Welcome, {{ $u->username ?? $u->name ?? $emailPrefix }}
                </h1>
                <p class="text-gray-600 mb-6">Manage your properties, check stats, and add new listings below.</p>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                    <div class="bg-white rounded-lg shadow p-4 text-center">
                        <p class="text-sm text-gray-600">Total Listings</p>
                        <h2 class="text-2xl font-bold text-indigo-700">{{ number_format($stats['total_listings'] ?? 0) }}
                        </h2>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4 text-center">
                        <p class="text-sm text-gray-600">Properties Reserved</p>
                        <h2 class="text-2xl font-bold text-green-600">
                            {{ number_format($stats['properties_reserved'] ?? 0) }}
                        </h2>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4 text-center">
                        <p class="text-sm text-gray-600">Total Revenue</p>
                        <h2 class="text-2xl font-bold text-yellow-600">Rs. {{ number_format($stats['total_revenue'] ?? 0) }}
                        </h2>
                    </div>
                </div>
            </div>

            <!-- Property Listing Form -->
            <div id="add-property" class="mb-12">
                <h2 class="text-2xl font-bold text-black mb-4">List a New Property</h2>
                <div class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded mb-4 text-sm">
                    Listing a property requires a fee of Rs. 1,000.
                </div>
                @php
                    $hasPreview = \Illuminate\Support\Facades\Route::has('properties.preview');
                @endphp
                <form method="POST" action="{{ $hasPreview ? route('properties.preview') : '#' }}"
                    enctype="multipart/form-data" @if(!$hasPreview) onsubmit="return false" @endif>
                    @if($hasPreview)
                        @csrf
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-1">Title</label>
                            <input type="text" name="title" class="w-full border border-gray-300 rounded px-3 py-2"
                                placeholder="e.g. Spacious 2BR in Nugegoda">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-semibold mb-1">Price (Rs.)</label>
                            <input type="number" name="price" class="w-full border border-gray-300 rounded px-3 py-2"
                                placeholder="e.g. 20000000" min="0" step="1">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 font-semibold mb-1">Location</label>
                            <input type="text" name="location" class="w-full border border-gray-300 rounded px-3 py-2"
                                placeholder="e.g. Colombo 05">
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
                        <textarea name="description" class="w-full border border-gray-300 rounded px-3 py-2" rows="4"
                            placeholder="Enter detailed description..."></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-semibold mb-1">Upload Property Image</label>
                        <input type="file" name="image"
                            class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>

                    <div class="text-right">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition
                                                   @if(!$hasStore) opacity-50 cursor-not-allowed @endif">
                            Submit Listing
                        </button>
                        @unless($hasStore)
                            <p class="text-xs text-gray-500 mt-2">Submit is disabled: route <code>properties.store</code> not
                                found.</p>
                        @endunless
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
                            @forelse ($listings as $p)
                                @php
                                    $label = $p->title ?? ('Property #' . $p->id);
                                    $price = isset($p->price) ? (float) $p->price : null;

                                    // Pending takes priority if not approved yet
                                    $pending = isset($p->is_available)
                                        ? ((int) $p->is_available === 0)
                                        : (isset($p->pstatus) && in_array(strtolower($p->pstatus), ['pending', 'pending_review']));

                                    // A property is "Reserved" if it has at least one reservation
                                    $reserved = ($p->reserved_count ?? 0) > 0;

                                    // Human-readable status (used only for fallback; chips are shown below)
                                    if ($pending) {
                                        $status = 'Pending';
                                    } elseif ($reserved) {
                                        $status = 'Reserved';
                                    } else {
                                        $status = 'Available';
                                    }

                                    $date = $p->created_at ?? null;

                                    // You requested: "should not be able to edit until admin approval"
                                    $canEdit = $hasEdit && !$pending;
                                @endphp

                                <tr>
                                    <td class="px-4 py-3">
                                        @if($hasShow)
                                            <a href="{{ route('properties.show', $p->id) }}"
                                                class="text-indigo-700 hover:underline">
                                                {{ $label }}
                                            </a>
                                        @else
                                            {{ $label }}
                                        @endif
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $price !== null ? 'Rs. ' . number_format($price) : 'N/A' }}
                                    </td>

                                    <td class="px-4 py-3">
                                        @if($pending)
                                            <span
                                                class="inline-flex items-center rounded px-2 py-0.5 text-xs font-semibold bg-amber-100 text-amber-800">
                                                Pending
                                            </span>
                                        @elseif($reserved)
                                            <span
                                                class="inline-flex items-center rounded px-2 py-0.5 text-xs font-semibold bg-green-100 text-green-800">
                                                Reserved
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center rounded px-2 py-0.5 text-xs font-semibold bg-indigo-100 text-indigo-800">
                                                Available
                                            </span>
                                        @endif>
                                    </td>

                                    <td class="px-4 py-3">
                                        {{ $date ? \Carbon\Carbon::parse($date)->format('Y-m-d') : '—' }}
                                    </td>

                                    <td class="px-4 py-3 text-center space-x-2">
                                        {{-- Edit: disabled while pending --}}
                                        @if($canEdit)
                                            <a href="{{ route('properties.edit', $p->id) }}"
                                                class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">
                                                Edit
                                            </a>
                                        @else
                                            <button class="bg-gray-300 text-gray-700 px-3 py-1 rounded text-xs cursor-not-allowed"
                                                title="Edit is disabled until admin approval">
                                                Edit
                                            </button>
                                        @endif

                                        {{-- Delete: allowed even before approval (owner-only; controller enforces) --}}
                                        @if($hasDestroy)
                                            <form action="{{ route('properties.destroy', $p->id) }}" method="POST" class="inline"
                                                onsubmit="return confirm('Delete this listing? This cannot be undone.')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="bg-red-500 text-white px-3 py-1 rounded text-xs hover:bg-red-600">
                                                    Delete
                                                </button>
                                            </form>
                                        @else
                                            <button class="bg-gray-300 text-gray-700 px-3 py-1 rounded text-xs cursor-not-allowed"
                                                title="Delete route not available">Delete</button>
                                        @endif

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                        You have no listings yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>


            <!-- Receipt Placeholder -->
            <div id="receipts" class="text-center">
                <h2 class="text-xl font-bold text-black mb-2">Download Your Listing Receipts</h2>
                <p class="text-sm text-gray-600 mb-4">These receipts will be available after payment integration.</p>
                <a href="#" class="inline-block bg-gray-300 text-gray-700 px-4 py-2 rounded cursor-not-allowed">
                    Download (coming soon)
                </a>
            </div>
        </div>
    </div>
@endsection