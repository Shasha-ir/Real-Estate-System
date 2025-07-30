@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 fle pb-32">

    {{-- Sidebar --}}
    <aside class="w-64 bg-white shadow-md p-6 hidden sm:block">
        <h2 class="text-xl font-bold mb-6 text-indigo-700">Admin Panel</h2>
        <nav class="space-y-4 text-sm text-gray-700">
            <a href="#dashboard" class="block hover:text-indigo-600">🏠 Dashboard</a>
            <a href="#users" class="block hover:text-indigo-600">👥 Manage Users</a>
            <a href="#properties" class="block hover:text-indigo-600">🏡 Manage Properties</a>
            <a href="#reports" class="block hover:text-indigo-600">📥 Export Reports</a>
            <a href="#" class="block text-red-600 hover:underline mt-4">🚪 Logout</a>
        </nav>
    </aside>

    {{-- Main Content --}}
    <main class="flex-1 p-6 pt-24 sm:pt-8 pb-44">
        <h1 class="text-3xl font-bold text-black mb-8 text-center" id="dashboard">Admin Dashboard</h1>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
            <div class="bg-white shadow p-6 text-center">
                <h2 class="text-sm text-gray-500 mb-1">Total Users</h2>
                <p class="text-2xl font-bold text-indigo-600">150</p>
            </div>
            <div class="bg-white shadow p-6 text-center">
                <h2 class="text-sm text-gray-500 mb-1">Properties Listed</h2>
                <p class="text-2xl font-bold text-indigo-600">48</p>
            </div>
            <div class="bg-white shadow p-6 text-center">
                <h2 class="text-sm text-gray-500 mb-1">Pending Approvals</h2>
                <p class="text-2xl font-bold text-red-500">7</p>
            </div>
        </div>

        {{-- User Management Table --}}
        <div class="bg-white rounded-xl shadow mb-12 p-6 overflow-x-auto" id="users">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-black">User Management</h2>
                <button class="text-indigo-600 text-sm hover:underline">+ Add New User</button>
            </div>
            <table class="w-full table-auto text-sm">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Role</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 1; $i <= 5; $i++)
                        @php
                            $status = $i % 3 == 0 ? 'Disabled' : 'Active';
                            $statusColor = $status === 'Active' ? 'text-green-600' : 'text-red-600';
                        @endphp
                        <tr class="border-t">
                            <td class="px-4 py-2">U00{{ $i }}</td>
                            <td class="px-4 py-2">User {{ $i }}</td>
                            <td class="px-4 py-2">user{{ $i }}@example.com</td>
                            <td class="px-4 py-2">{{ $i % 2 == 0 ? 'Seller' : 'Buyer' }}</td>
                            <td class="px-4 py-2 font-medium {{ $statusColor }}">{{ $status }}</td>
                            <td class="px-4 py-2">
                                <a href="#" class="text-indigo-600 hover:underline text-xs">View</a> |
                                <a href="#" class="text-red-600 hover:underline text-xs">Disable</a>
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        {{-- Property Review Table --}}
        <div class="bg-white rounded-xl shadow p-6 overflow-x-auto" id="properties">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold text-black">Property Listings</h2>
                <button class="text-indigo-600 text-sm hover:underline">+ Add New Property</button>
            </div>
            <table class="w-full table-auto text-sm">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="px-4 py-2 text-left">ID</th>
                        <th class="px-4 py-2 text-left">Title</th>
                        <th class="px-4 py-2 text-left">Seller</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Location</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($j = 1; $j <= 5; $j++)
                        @php
                            $status = $j % 2 == 0 ? 'Approved' : 'Pending';
                            $statusColor = $status === 'Approved' ? 'text-green-600' : 'text-red-600';
                        @endphp
                        <tr class="border-t">
                            <td class="px-4 py-2">P00{{ $j }}</td>
                            <td class="px-4 py-2">Property {{ $j }}</td>
                            <td class="px-4 py-2">Seller {{ $j }}</td>
                            <td class="px-4 py-2 font-medium {{ $statusColor }}">{{ $status }}</td>
                            <td class="px-4 py-2">Colombo {{ 5 + $j }}</td>
                            <td class="px-4 py-2">
                                <a href="#" class="text-indigo-600 hover:underline text-xs">Details</a> |
                                <a href="#" class="text-green-600 hover:underline text-xs">Approve</a>
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        {{-- Report Export Placeholder --}}
        <div id="reports" class="mt-12 mb-32  text-center">
            <h2 class="text-xl font-semibold text-black mb-2">Export Reports</h2>
            <p class="text-sm text-gray-600 mb-4">Download monthly reports in PDF format.</p>
            <button class="px-6 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm">
                Download Report (PDF)
            </button>
        </div>
    </main>
</div>
@endsection
