@php($u = auth()->user())
@if (session('success'))
    <div class="mb-6 rounded-lg bg-green-100 text-green-800 px-4 py-3">
        {{ session('success') }}
    </div>
@endif

<div id="profile" class="mb-10 bg-white/90 backdrop-blur-md rounded-xl shadow p-6">
    <h2 class="text-2xl font-bold text-black mb-4">Profile & Account</h2>

    {{-- Update Email & Username --}}
    <form method="POST" action="{{ route('profile.update') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        @csrf
        @method('patch')

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
            <input name="email" type="email" value="{{ old('email', $u->email) }}"
                   class="w-full border border-gray-300 rounded px-3 py-2" required>
            @error('email') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Username</label>
            <input name="username" type="text" value="{{ old('username', $u->username) }}"
                   class="w-full border border-gray-300 rounded px-3 py-2" required>
            @error('username') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-end">
            <button class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded px-4 py-2">
                Save Changes
            </button>
        </div>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Change Password --}}
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <h3 class="text-lg font-semibold text-black mb-3">Change Password</h3>
            <form method="POST" action="{{ route('profile.password') }}" class="space-y-3">
                @csrf
                @method('put')

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Current Password</label>
                    <input name="current_password" type="password" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    @error('current_password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">New Password</label>
                    <input name="password" type="password" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Confirm New Password</label>
                    <input name="password_confirmation" type="password" class="w-full border border-gray-300 rounded px-3 py-2" required>
                </div>

                <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded px-4 py-2">
                    Update Password
                </button>
            </form>
        </div>

        {{-- Delete Account --}}
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <h3 class="text-lg font-semibold text-black mb-3">Delete Account</h3>
            <p class="text-sm text-gray-600 mb-3">This action is permanent. Type your password to confirm.</p>
            <form method="POST" action="{{ route('profile.destroy') }}" class="space-y-3"
                  onsubmit="return confirm('Are you sure you want to delete your account? This cannot be undone.')">
                @csrf
                @method('delete')

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                    <input name="password" type="password" class="w-full border border-gray-300 rounded px-3 py-2" required>
                    @error('password') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <button class="bg-red-600 hover:bg-red-700 text-white font-semibold rounded px-4 py-2">
                    Delete My Account
                </button>
            </form>
        </div>
    </div>
</div>
