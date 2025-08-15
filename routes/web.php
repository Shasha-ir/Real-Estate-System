<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BuyerDashboardController;
use App\Http\Controllers\SellerDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\PropertyListingController;

/*
|--------------------------------------------------------------------------
| Public pages
|--------------------------------------------------------------------------
*/
Route::view('/', 'home')->name('home'); // single canonical home route

// Public browse pages (order: specific first, then dynamic)
Route::get('/properties', [PropertyController::class, 'index'])->name('properties.index');
Route::get('/properties/residential', [PropertyController::class, 'residential'])->name('properties.residential');
Route::get('/properties/commercial', [PropertyController::class, 'commercial'])->name('properties.commercial');
Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');

// Marketing/static
Route::view('/contact', 'contact');
Route::view('/sellers', 'sellers');

// TEMP public preview of admin dashboard (do NOT use in production)
Route::view('/dashboard/admin-preview', 'dashboard.admin')->name('admin.preview');

/*
|--------------------------------------------------------------------------
| Auth (login/register/logout)
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboards
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
    Route::get('/dashboard/buyer', [BuyerDashboardController::class, 'index'])->name('dashboard.buyer');
    Route::get('/dashboard/seller', [SellerDashboardController::class, 'index'])->name('dashboard.seller');
    Route::get('/dashboard/admin', fn () => view('dashboard.admin'))->name('dashboard.admin');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Reservations (parameterized) — ONLY inside auth
    Route::get('/reserve/{property}', [ReservationController::class, 'create'])->name('reservation.form');
    Route::post('/reserve/{property}', [ReservationController::class, 'store'])->name('reservation.process');
    Route::post('/reserve/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservation.cancel'); // optional

    // Seller: delete property
    Route::delete('/properties/{property}', [PropertyController::class, 'destroy'])->name('properties.destroy');

    // Seller listing flow (PRG-safe)
    Route::post('/properties/preview', [PropertyListingController::class, 'preview'])->name('properties.preview'); // POST
    Route::get('/properties/preview', fn () => redirect()->route('properties.checkout'));
    Route::get('/properties/checkout', [PropertyListingController::class, 'checkout'])->name('properties.checkout'); // GET
    Route::post('/properties/confirm', [PropertyListingController::class, 'confirm'])->name('properties.confirm');  // POST


});

// If you still need /properties/store publicly (POST), leave it here. It's POST so it won't collide with GET /properties/{property}.
Route::post('/properties/store', [PropertyController::class, 'store'])->name('properties.store');

// Admin portal login (public)
Route::view('/admin/login', 'admin.login')->name('admin.login');
