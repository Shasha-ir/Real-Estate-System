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
            ->where('custom_id', $request->user_id)
            ->with('role')
            ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);

            $router = app('router');

            if (optional($user->role)->name === 'seller') {
                // Prefer new canonical name, fall back to legacy or raw URL
                if ($router->has('dashboard.seller')) {
                    return redirect()->route('dashboard.seller');
                } elseif ($router->has('seller.dashboard')) {
                    return redirect()->route('seller.dashboard');
                }
                return redirect('/dashboard/seller');
            }

            if (optional($user->role)->name === 'buyer') {
                if ($router->has('dashboard.buyer')) {
                    return redirect()->route('dashboard.buyer');
                } elseif ($router->has('buyer.dashboard')) {
                    return redirect()->route('buyer.dashboard');
                }
                return redirect('/dashboard/buyer');
            }

            // Fallback for any other role
            if ($router->has('dashboard')) {
                return redirect()->route('dashboard');
            }
            return redirect('/'); // final fallback
        }

        return back()->with('error', 'Invalid credentials. Please try again.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

}
