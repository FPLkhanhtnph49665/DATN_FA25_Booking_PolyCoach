<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Route;
use App\Models\Bus;
use App\Models\Trip;
use App\Models\Ticket;
use App\Models\Passenger;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Contact;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tạo Users: 5 admin + 8 khách hàng
        User::factory()->count(5)->state(['role' => 'admin'])->create();
        $customers = User::factory()->count(8)->state(['role' => 'customer'])->create();

        // 2. Tạo Routes: 20 tuyến
        $routes = Route::factory()->count(20)->create();

        // 3. Tạo Buses: 50 xe
        $buses = Bus::factory()->count(50)->create();

        // 4. Tạo Trips: 20 chuyến
        $trips = Trip::factory()
            ->count(20)
            ->state(function () use ($routes, $buses) {
                return [
                    'route_id' => $routes->random()->id,
                    'bus_id' => $buses->random()->id,
                ];
            })
            ->create();

        // 5. Tạo Tickets: mỗi trip 1-3 vé
        $tickets = collect();
        foreach ($trips as $trip) {
            $numTickets = rand(1, 3);
            for ($i = 0; $i < $numTickets; $i++) {
                $ticket = Ticket::factory()->state([
                    'trip_id' => $trip->id,
                    'user_id' => $customers->random()->id,
                    'so_ghe' => rand(1, $trip->bus->so_ghe),
                ])->create();
                $tickets->push($ticket);
            }
        }
$tickets = Ticket::factory()
    ->count(35)
    ->state(function () use ($customers, $trips) {
        return [
            'user_id' => $customers->random()->id, // đây phải có
            'trip_id' => $trips->random()->id,
        ];
    })
    ->create();

        // 6. Tạo Passengers: mỗi ticket 1-5 hành khách, ghế không trùng
        foreach ($tickets as $ticket) {
            $tripSeats = range(1, $ticket->trip->bus->so_ghe);
            shuffle($tripSeats);
            $numPassengers = min(rand(1, 5), count($tripSeats));
            for ($i = 0; $i < $numPassengers; $i++) {
                Passenger::factory()->state([
                    'ticket_id' => $ticket->id,
                    'seat_number' => $tripSeats[$i],
                ])->create();
            }
        }

        // 7. Tạo Payments: mỗi ticket 1 thanh toán
        foreach ($tickets as $ticket) {
            Payment::factory()->state([
                'ticket_id' => $ticket->id,
                'user_id' => $ticket->user_id,
            ])->create();
        }

        // 8. Tạo Reviews: mỗi trip vài đánh giá
        foreach ($trips as $trip) {
            $numReviews = rand(1, 5);
            for ($i = 0; $i < $numReviews; $i++) {
                Review::factory()->state([
                    'trip_id' => $trip->id,
                    'user_id' => $customers->random()->id,
                ])->create();
            }
        }

        // 9. Tạo Contacts: 25 liên hệ
        Contact::factory()->count(25)->create();
    }
}
