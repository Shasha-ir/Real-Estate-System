<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\Auth\RegisterController;

// Static Pages
Route::view('/', 'home');
Route::view('/properties', 'properties.index');
Route::view('/properties/residential', 'properties.residential');
Route::view('/properties/commercial', 'properties.commercial');
Route::view('/contact', 'contact');

Route::view('/sellers', 'sellers');

// Auth Routes (Custom Controller-based)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboards (Authenticated Users Only)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    Route::get('/dashboard/seller', fn() => view('dashboard.seller'))->name('seller.dashboard');
    Route::get('/dashboard/buyer', fn() => view('dashboard.buyer'))->name('buyer.dashboard');
    Route::get('/dashboard/admin', fn() => view('dashboard.admin'))->name('admin.dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Portal
Route::view('/admin/login', 'admin.login')->name('admin.login');

// Property Routes
Route::post('/properties/store', [PropertyController::class, 'store'])->name('properties.store');

// Reservation
Route::view('/reserve', 'reserve')->name('reservation.form');
Route::post('/reserve', function () {
    return redirect()->route('buyer.dashboard')->with('message', 'Reservation complete!');
})->name('reservation.process');
