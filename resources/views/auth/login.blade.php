@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-fixed bg-cover bg-center flex flex-col items-center justify-center pt-24 pb-24 px-4"
    style="background-image: url('{{ asset('images/login.png') }}')">

    <div class="bg-white bg-opacity-60 shadow-2xl rounded-2xl p-10 w-full max-w-md backdrop-blur-md transition-all duration-500 ease-in-out" data-aos="zoom-in">
        <h1 class="text-3xl font-extrabold text-black mb-6 text-center tracking-wide" data-aos="fade-down" data-aos-delay="100">
            Login to IR Real Estates
        </h1>

        <form method="POST" action="{{ route('login') }}" data-aos="fade-up" data-aos-delay="200">
            @csrf

            <!-- Username -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2" for="username">Username</label>
                <input id="username" name="username" type="text" required autofocus
                    class="w-full px-4 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="Enter your username">
            </div>

            <!-- Buyer/Seller ID -->
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2" for="user_id">Seller/Buyer ID</label>
                <input id="user_id" name="user_id" type="text" required
                    class="w-full px-4 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="e.g. S001 or B054">
            </div>

            <!-- Password -->
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-2" for="password">Password</label>
                <input id="password" name="password" type="password" required
                    class="w-full px-4 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="••••••••">
            </div>

            <div class="text-center">
                <button type="submit"
                    class="w-full bg-indigo-600 text-white px-4 py-2 rounded font-semibold hover:bg-indigo-700 transition duration-300 shadow-md">
                     Login
                </button>
            </div>
        </form>

        <!-- Register Redirect -->
        <p class="text-sm text-center text-gray-600 mt-4" data-aos="fade-up" data-aos-delay="300">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-indigo-600 hover:underline font-medium">Register here</a>
        </p>

        <!-- Temporary Dashboard Preview Buttons -->
        <div class="mt-8" data-aos="fade-up" data-aos-delay="400">
            <h2 class="text-sm font-semibold text-gray-600 mb-4 text-center">🔍 Temporary Dashboard Previews</h2>
            <div class="flex justify-center gap-3 flex-wrap">
                <button onclick="showPreviewModal('seller')"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm shadow hover:scale-105">
                    Seller Dashboard
                </button>
                <button onclick="showPreviewModal('buyer')"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm shadow hover:scale-105">
                    Buyer Dashboard
                </button>
                <a href="/admin/login"
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition text-sm shadow hover:scale-105">
                    Admin Portal
                </a>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div id="previewModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full p-6 animate-fade-in-down">
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

<style>
    @keyframes fade-in-down {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fade-in-down {
        animation: fade-in-down 0.3s ease-out;
    }
</style>
@endsection
