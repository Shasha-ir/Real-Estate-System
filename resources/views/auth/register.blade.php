@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-cover bg-center flex items-start justify-start px-4 pt-24 pb-24" style="background-image: url('{{ asset('images/register.png') }}')">
    <div class="bg-indigo-700 bg-opacity-10 shadow-xl rounded-xl w-full max-w-3xl p-10">
        <h1 class="text-3xl font-bold text-black mb-6">Create an Account</h1>

        {{-- Simulated success message --}}
        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-6 text-sm">
                {{ session('success') }}<br>
                <strong>Your issued ID is:</strong> <span class="font-mono text-indigo-700">{{ session('user_id') }}</span><br>
                <span class="text-red-600">Please remember this ID to log in to the system.</span>
                <a href="{{ route('login') }}" class="block mt-2 underline text-indigo-600">Go to login</a>
            </div>
        @endif

        <form method="POST" action="#">
            {{-- @csrf --}}

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Full Name -->
                <div>
                    <label for="name" class="block font-medium text-sm text-gray-700 mb-1">Full Name</label>
                    <input id="name" type="text" name="name" required
                        class="w-full border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:border-indigo-500"
                        placeholder="e.g. John Doe">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block font-medium text-sm text-gray-700 mb-1">Email</label>
                    <input id="email" type="email" name="email" required
                        class="w-full border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:border-indigo-500"
                        placeholder="example@email.com">
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block font-medium text-sm text-gray-700 mb-1">Username</label>
                    <input id="username" type="text" name="username" required
                        class="w-full border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:border-indigo-500"
                        placeholder="Choose a username">
                </div>

                <!-- Role (Buyer/Seller) -->
                <div>
                    <label class="block font-medium text-sm text-gray-700 mb-1">Register As</label>
                    <div class="flex items-center gap-6 mt-2">
                        <label class="flex items-center">
                            <input type="radio" name="role" value="buyer" required
                                class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Buyer</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="role" value="seller" required
                                class="text-indigo-600 focus:ring-indigo-500">
                            <span class="ml-2 text-sm text-gray-700">Seller</span>
                        </label>
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block font-medium text-sm text-gray-700 mb-1">Password</label>
                    <input id="password" type="password" name="password" required
                        class="w-full border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:border-indigo-500"
                        placeholder="••••••••">
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block font-medium text-sm text-gray-700 mb-1">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="w-full border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring focus:border-indigo-500"
                        placeholder="••••••••">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-8">
                <button type="submit"
                    class="w-full bg-indigo-600 text-white py-2 px-4 rounded font-semibold hover:bg-indigo-700 transition">
                    Register
                </button>
            </div>

            <!-- Login Redirect -->
            <p class="text-sm text-gray-600 mt-4">
                Already have an account?
                <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Login here</a>
            </p>
        </form>
    </div>
</div>
@endsection
