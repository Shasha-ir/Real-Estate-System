@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-fixed bg-cover bg-center flex flex-col items-center justify-center pt-24 pb-24 px-4"
        style="background-image: url('{{ asset('images/login.png') }}')">

        <div class="bg-white bg-opacity-60 shadow-2xl rounded-2xl p-10 w-full max-w-md backdrop-blur-md transition-all duration-500 ease-in-out"
            data-aos="zoom-in">
            <h1 class="text-3xl font-extrabold text-black mb-6 text-center tracking-wide" data-aos="fade-down"
                data-aos-delay="100">
                Login to IR Real Estates
            </h1>

            @if(session('error'))
                <div class="bg-red-100 text-red-800 px-4 py-2 rounded text-sm mb-4 text-center">
                    {{ session('error') }}
                </div>
            @endif

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

            <!-- Admin Portal -->
            <div class="mt-6 text-center">
                <a href="/admin/login"
                    class="inline-block bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 text-sm font-medium transition shadow hover:scale-105">
                    Admin Portal
                </a>
            </div>
        </div>
    </div>
@endsection