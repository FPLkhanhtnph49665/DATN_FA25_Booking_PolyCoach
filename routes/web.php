<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\BusController;
use App\Http\Controllers\Admin\TripController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PassengerController;

Route::get('/', function () {
    return view('comingsoon');
})->name('comingsoon');


Route::prefix('admin')->name('admin.')->group(function () {
    Route::view('/', 'admin.dashboard')->name('dashboard');
Route::resource('users', UserController::class);
Route::resource('routes', RouteController::class);
Route::resource('buses', BusController::class);
Route::resource('trips', TripController::class);
Route::resource('tickets', TicketController::class);
Route::resource('passengers', PassengerController::class);
Route::resource('payments', PaymentController::class);
Route::resource('reviews', ReviewController::class);
Route::resource('contacts', ContactController::class);
    // Route::view('users', 'admin.users.index')->name('users.index');
    // Route::view('routes', 'admin.routes.index')->name('routes.index');
    // Route::view('buses', 'admin.buses.index')->name('buses.index');
    // Route::view('trips', 'admin.trips.index')->name('trips.index');
    // Route::view('tickets', 'admin.tickets.index')->name('tickets.index');
    // Route::view('payments', 'admin.payments.index')->name('payments.index');
    // Route::view('reviews', 'admin.reviews.index')->name('reviews.index');
    // Route::view('contacts', 'admin.contacts.index')->name('contacts.index');
    // Route::view('passengers', 'admin.passengers.index')->name('passengers.index');
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
