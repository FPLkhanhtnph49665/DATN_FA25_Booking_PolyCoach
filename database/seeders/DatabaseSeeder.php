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
        // --------------------------
        // 1. Tạo Users
        // --------------------------
        $admins = User::factory()
            ->count(5)
            ->state(['role' => 'admin'])
            ->sequence(fn ($seq) => [
                'user_code' => 'DATN_FA25_PoLyCoach_' . str_pad($seq->index + 1, 4, '0', STR_PAD_LEFT)
            ])
            ->create();

        $customers = User::factory()
            ->count(8)
            ->state(['role' => 'customer'])
            ->sequence(fn ($seq) => [
                'user_code' => 'DATN_FA25_PoLyCoach_' . str_pad($seq->index + 6, 4, '0', STR_PAD_LEFT)
            ])
            ->create();

        // --------------------------
        // 2. Tạo Routes
        // --------------------------
        $routes = Route::factory()->count(20)->create();

        // --------------------------
        // 3. Tạo Buses
        // --------------------------
        $buses = Bus::factory()->count(50)->create();

        // --------------------------
        // 4. Tạo Trips
        // --------------------------
        $trips = Trip::factory()
            ->count(20)
            ->state(fn() => [
                'route_id' => $routes->random()->id,
                'bus_id' => $buses->random()->id,
            ])
            ->create();

        // --------------------------
        // 5. Tạo Tickets và Passengers
        // --------------------------
        $tickets = collect();

        foreach ($trips as $trip) {
            $totalSeats = $trip->bus->so_ghe;
            $usedSeats = []; // lưu ghế đã được đặt trong trip

            $numTickets = rand(1, 3);
            for ($i = 0; $i < $numTickets; $i++) {
                $user = $customers->random();
                $remainingSeats = array_diff(range(1, $totalSeats), $usedSeats);
                $numSeatsTicket = rand(1, min(5, count($remainingSeats)));
                $ticketSeats = array_slice($remainingSeats, 0, $numSeatsTicket);

                // Tạo ticket
                $ticket = Ticket::factory()->state([
                    'trip_id' => $trip->id,
                    'user_id' => $user->id,
                    'so_ghe' => $numSeatsTicket,
                    'trang_thai' => 'paid',
                    'phuong_thuc_thanh_toan' => ['Tiền mặt', 'Momo', 'VNPay'][array_rand(['Tiền mặt', 'Momo', 'VNPay'])],
                ])->create();

                // Tạo passengers cho ticket
                foreach ($ticketSeats as $seat) {
                    Passenger::factory()->state([
                        'ticket_id' => $ticket->id,
                        'seat_number' => $seat,
                        'name' => $user->full_name,
                        'phone' => $user->phone,
                        'age' => rand(18, 60),
                    ])->create();
                    $usedSeats[] = $seat; // đánh dấu ghế đã dùng
                }

                $tickets->push($ticket);
            }
        }

        // --------------------------
        // 6. Tạo Payments cho mỗi ticket
        // --------------------------
        foreach ($tickets as $ticket) {
            Payment::factory()->state([
                'ticket_id' => $ticket->id,
                'user_id' => $ticket->user_id,
            ])->create();
        }

        // --------------------------
        // 7. Tạo Reviews (chỉ user đã mua vé của trip)
        // --------------------------
        foreach ($trips as $trip) {
            // lấy tất cả user đã mua vé cho trip
            $usersBought = $tickets->where('trip_id', $trip->id)->pluck('user_id')->unique();

            foreach ($usersBought as $userId) {
                $numReviews = rand(0, 2); // mỗi user có thể viết 0-2 review
                for ($i = 0; $i < $numReviews; $i++) {
                    Review::factory()->state([
                        'trip_id' => $trip->id,
                        'user_id' => $userId,
                    ])->create();
                }
            }
        }

        // --------------------------
        // 8. Tạo Contacts
        // --------------------------
        Contact::factory()->count(25)->create();
    }
}
