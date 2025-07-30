@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-800 flex items-center justify-center px-4 py-16">
        <div class="bg-white shadow-xl rounded-xl w-full max-w-md p-8">
            <h2 class="text-3xl font-bold text-black mb-6 text-center">Admin Portal Login</h2>

            <form method="POST" action="#">
                {{-- @csrf --}}

                <!-- Admin ID -->
                <div class="mb-4">
                    <label for="admin_id" class="block text-gray-700 font-semibold mb-2">Admin ID</label>
                    <input type="text" id="admin_id" name="admin_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:border-indigo-500"
                        placeholder="e.g. A001">
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:border-indigo-500"
                        placeholder="••••••••">
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 text-white py-2 rounded font-semibold hover:bg-indigo-700 transition">
                    Login
                </button>

                <!-- Temporary Admin Dashboard Preview -->
                <div class="mt-6 text-center">
                    <h3 class="text-sm text-gray-400 mb-2">Temporary Preview</h3>
                    <a href="/dashboard/admin"
                        class="inline-block px-4 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700 transition">
                        Preview Admin Dashboard
                    </a>
                    <p class="text-xs text-gray-500 mt-2">
                        This is a placeholder preview. Authentication will be added in full-stack version.
                    </p>
                </div>


            </form>

            <!-- Note -->
            <p class="text-sm text-center text-red-600 mt-4">
                This portal is for authorized administrators only.
            </p>
        </div>
    </div>
@endsection