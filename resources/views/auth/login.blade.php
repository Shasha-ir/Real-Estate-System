@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-fixed bg-cover bg-center flex flex-col items-center justify-center pt-24 pb-24 px-4"
        style="background-image: url('{{ asset('images/login.png') }}')">
        <div class="bg-white bg-opacity-50  shadow-xl rounded-xl p-8 w-full max-w-md">
            <h1 class="text-3xl font-bold text-black-700 mb-6 text-center">Login to IR Real Estates</h1>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Username -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2" for="username">Username</label>
                    <input id="username" name="username" type="text" required autofocus
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:border-indigo-500"
                        placeholder="Enter your username">
                </div>

                <!-- Buyer/Seller ID -->
                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2" for="user_id">Seller/Buyer ID</label>
                    <input id="user_id" name="user_id" type="text" required
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:border-indigo-500"
                        placeholder="e.g. S001 or B054">
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label class="block text-gray-700 font-semibold mb-2" for="password">Password</label>
                    <input id="password" name="password" type="password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:border-indigo-500"
                        placeholder="••••••••">
                </div>

                <div class="text-center">
                    <button type="submit"
                        class="w-full bg-indigo-600 text-white px-4 py-2 rounded font-semibold hover:bg-indigo-700 transition">
                        Login
                    </button>
                </div>
            </form>

            <!-- Register Redirect -->
            <p class="text-sm text-center text-gray-600 mt-4">
                Don't have an account?
                <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">Register here</a>
            </p>

            <!-- Temporary Dashboard Preview Buttons -->
            <div class="mt-8">
                <h2 class="text-sm font-semibold text-gray-600 mb-4 text-center">Temporary Dashboard Previews</h2>
                <div class="flex justify-center gap-4 flex-wrap">
                    <button onclick="showPreviewModal('seller')"
                        class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition text-sm">
                        Seller Dashboard
                    </button>
                    <button onclick="showPreviewModal('buyer')"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition text-sm">
                        Buyer Dashboard
                    </button>
                    <a href="/admin/login"
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition text-sm">
                        Admin Portal
                    </a>
                </div>

            </div>
        </div>

        <!-- Preview Modal -->
        <div id="previewModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
            <div class="bg-white rounded-xl shadow-lg max-w-md w-full p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Temporary Dashboard Preview</h3>
                <p class="text-sm text-gray-700 mb-6">
                    You are navigating to a temporary UI to preview the dashboard design.<br>
                    After full-stack implementation, this action will require authentication.
                </p>
                <div class="flex justify-end gap-4">
                    <button onclick="closePreviewModal()" class="text-gray-600 hover:text-gray-800">Cancel</button>
                    <a id="proceedLink" href="#"
                        class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 text-sm">Proceed</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showPreviewModal(role) {
            const modal = document.getElementById('previewModal');
            const link = document.getElementById('proceedLink');
            link.href = role === 'seller' ? '/dashboard/seller' : '/dashboard/buyer';
            modal.classList.remove('hidden');
        }

        function closePreviewModal() {
            document.getElementById('previewModal').classList.add('hidden');
        }
    </script>
@endsection