<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\BusController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\TripController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RouteController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Admin\PointFareController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Client\TripController as ClientTripController;
use App\Http\Controllers\Client\BookingController as ClientBookingController;
use App\Http\Controllers\Client\ContactController as ClientContactController;

// Route::get('/', function () {
//     return view('comingsoon');
// })->name('comingsoon');


Route::get('/', [HomeController::class, 'index'])->name('client.home');
Route::get('/lich-trinh', [ClientTripController::class, 'index'])->name('client.trips');
Route::get('/tim-chuyen-di', [ClientTripController::class, 'searchTrips'])->name('client.searchTrips');
Route::get('/dat-ve', [ClientTripController::class, 'show'])->name('client.trips.show');
Route::get('/lien-he', [ClientContactController::class, 'showForm'])->name('client.contact.show');
Route::post('/lien-he', [ClientContactController::class, 'submit'])->name('client.contact.submit');

Route::middleware('auth')->group(function () {
    Route::get('/thong-tin-tai-khoan', [AuthenticatedSessionController::class, 'show'])
        ->name('client.account.show');

    Route::post('/thong-tin-tai-khoan', [AuthenticatedSessionController::class, 'update'])
        ->name('client.account.update');
    Route::get('/thong-tin-tai-khoan/lich-su-mua-ve', [AuthenticatedSessionController::class, 'ticketHistory'])
        ->name('client.account.tickets');
    Route::post('/dat-ve', [ClientBookingController::class, 'store'])
        ->name('client.bookings.store');

    Route::get('/da-dat-ve/{booking}', [ClientBookingController::class, 'show'])
        ->name('client.bookings.show');

    Route::get('/api/get-fare', [ClientBookingController::class, 'getFare'])
        ->name('client.bookings.getFare');

    Route::get('trips/{trip}/select-seat', [TripController::class, 'selectSeat'])
        ->name('client.trips.select-seat');
});


Route::prefix('admin')->name('admin.')->middleware(['auth', AdminMiddleware::class])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    route::resource('cities', CityController::class);
    route::resource('point_fares', PointFareController::class);
    Route::resource('routes', RouteController::class);
    Route::resource('buses', BusController::class);
    Route::resource('trips', TripController::class);
    Route::resource('bookings', BookingController::class);
    Route::resource('tickets', TicketController::class);
    Route::resource('passengers', PassengerController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('reviews', ReviewController::class);
    Route::resource('contacts', ContactController::class);

});

require __DIR__ . '/auth.php';