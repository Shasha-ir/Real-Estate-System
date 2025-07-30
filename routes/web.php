<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Route::get('/properties', function () {
    return view('properties.index');
});

Route::view('/properties/residential', 'properties.residential');
Route::view('/properties/commercial', 'properties.commercial');
Route::view('/contact', 'contact');
Route::view('/sellers', 'sellers');
Route::view('/dashboard/seller', 'dashboard.seller');
Route::view('/dashboard/buyer', 'dashboard.buyer');
Route::view('/admin/login', 'admin.login');
Route::view('/dashboard/admin', 'dashboard.admin');
Route::view('/register', 'auth.register')->name('register');



Route::post('/properties/store', function () {
    return 'Pretend this saved a property.';
})->name('properties.store');

Route::view('/reserve', 'reserve')->name('reservation.form');

// Placeholder POST route for backend later
Route::post('/reserve', function () {
    return redirect('/dashboard/buyer')->with('message', 'Reservation complete!');
})->name('reservation.process');




Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
