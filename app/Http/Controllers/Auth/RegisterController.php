<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Notifications\UserRegisteredNotification;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'role' => 'required|in:buyer,seller',
        ]);

        $role = Role::where('name', $request->role)->firstOrFail();
        $prefix = $role->name === 'seller' ? 'S' : 'B';
        $count = User::where('role_id', $role->id)->count() + 1;
        $customId = $prefix . str_pad($count, 3, '0', STR_PAD_LEFT);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'custom_id' => $customId,
            'password' => Hash::make($request->password),
            'role_id' => $role->id,
        ]);

        // Send email
        $user->notify(new UserRegisteredNotification($customId));

        return redirect()->route('register')->with([
            'success' => 'Successfully registered. Please check your email to verify.',
            'user_id' => $customId
        ]);
    }
}
