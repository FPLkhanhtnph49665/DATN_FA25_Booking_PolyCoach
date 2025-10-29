<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
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


Route::prefix('admin')->name('admin.')->middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::view('/', 'admin.dashboard')->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('bus-routes', RouteController::class);
    Route::resource('buses', BusController::class);
    Route::resource('trips', TripController::class);
    Route::resource('tickets', TicketController::class);
    Route::resource('passengers', PassengerController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('reviews', ReviewController::class);
    Route::resource('contacts', ContactController::class);
});



Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
