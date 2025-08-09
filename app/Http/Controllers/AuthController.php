<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;



class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');

    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }


    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'role_id' => 'required|in:2,3',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'role_id' => $request->role_id,
            'password' => Hash::make($request->password),
            'custom_id' => $this->generateCustomId($request->role_id),
        ]);


        Auth::login($user);
        return redirect('/home');
    }
    private function generateCustomId($roleId)
    {
        $prefix = $roleId == 2 ? 'S' : 'B';
        $latest = User::where('role_id', $roleId)->orderByDesc('id')->first();
        $number = $latest ? ((int) filter_var($latest->custom_id, FILTER_SANITIZE_NUMBER_INT)) + 1 : 1;
        return $prefix . str_pad($number, 3, '0', STR_PAD_LEFT); // e.g., B001 or S002
    }


    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'user_id' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)
            ->where('custom_id', $request->user_id) // assumes your user table has custom_id like "S001" or "B002"
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            if ($user->role->name === 'seller') {
                return redirect()->route('seller.dashboard');
            } elseif ($user->role->name === 'buyer') {
                return redirect()->route('buyer.dashboard');
            } else {
                return redirect()->route('home'); // fallback
            }
        }

        return redirect()->back()->with('error', 'Invalid credentials. Please try again.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

}
