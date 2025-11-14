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
                'user_code' => 'DATN_FA25_PoLyCoach_Admin_' . str_pad($seq->index + 1, 4, '0', STR_PAD_LEFT)
            ])
            ->create();

        $customers = User::factory()
            ->count(8)
            ->state(['role' => 'user']) // ⚠ khớp enum('admin','user')
            ->sequence(fn ($seq) => [
                'user_code' => 'DATN_FA25_PoLyCoach_User_' . str_pad($seq->index + 6, 4, '0', STR_PAD_LEFT)
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
            ->state(fn () => [
                'route_id' => $routes->random()->id,
                'bus_id'   => $buses->random()->id,
            ])
            ->create();

        // --------------------------
        // 5. Tạo Tickets và Passengers
        // --------------------------
        $tickets = collect();

        foreach ($trips as $trip) {
            $bus = $trip->bus;
            // fallback 40 ghế nếu factory bus không set so_ghe
            $totalSeats = $bus && $bus->so_ghe ? (int) $bus->so_ghe : 40;

            // ====== SINH DANH SÁCH MÃ GHẾ A01, A02,... ======
            $allSeatCodes = [];
            $rows = ['A', 'B', 'C', 'D']; // giống Trip::getAvailableSeatsAttribute
            $cols = range(1, ceil($totalSeats / count($rows)));

            $count = 0;
            foreach ($rows as $r) {
                foreach ($cols as $c) {
                    $code = $r . str_pad($c, 2, '0', STR_PAD_LEFT); // A01, A02,...
                    $allSeatCodes[] = $code;
                    $count++;
                    if ($count >= $totalSeats) {
                        break 2;
                    }
                }
            }

            $usedSeatCodes = []; // lưu mã ghế đã đặt cho trip này

            // mỗi trip có 1–3 ticket, nhưng sẽ KHÔNG lấp full ghế
            $numTickets = rand(1, 3);

            for ($i = 0; $i < $numTickets; $i++) {
                // nếu còn <= 1 ghế thì dừng (đảm bảo còn ghế trống)
                if (count($usedSeatCodes) >= $totalSeats - 1) {
                    break;
                }

                $user = $customers->random();

                // ghế còn lại chưa dùng
                $remainingSeatCodes = array_values(array_diff($allSeatCodes, $usedSeatCodes));
                if (empty($remainingSeatCodes)) {
                    break;
                }

                // số ghế tối đa ticket này được đặt
                $maxSeatsThisTicket = min(
                    5,                                     // 1 vé max 5 ghế
                    count($remainingSeatCodes),            // không vượt ghế còn
                    ($totalSeats - 1) - count($usedSeatCodes) // chừa ít nhất 1 ghế
                );

                if ($maxSeatsThisTicket <= 0) {
                    break;
                }

                $numSeatsTicket = rand(1, $maxSeatsThisTicket);

                // lấy ngẫu nhiên N ghế
                shuffle($remainingSeatCodes);
                $ticketSeatCodes = array_slice($remainingSeatCodes, 0, $numSeatsTicket);

                // Tạo ticket
                $methods = ['cash', 'momo', 'bank']; // khớp Ticket::getPhuongThucThanhToanLabelAttribute
                $ticket = Ticket::factory()->state([
                    'trip_id'                => $trip->id,
                    'user_id'                => $user->id,
                    'so_ghe'                 => $numSeatsTicket,
                    'trang_thai'             => 'paid',
                    'phuong_thuc_thanh_toan' => $methods[array_rand($methods)],
                ])->create();

                // Tạo passengers cho ticket
                foreach ($ticketSeatCodes as $code) {
                    Passenger::factory()->state([
                        'ticket_id'   => $ticket->id,
                        'seat_number' => $code,               // A01, A02...
                        'name'        => $user->full_name,
                        'phone'       => $user->phone,
                        'age'         => rand(18, 60),
                    ])->create();

                    $usedSeatCodes[] = $code;
                }

                $tickets->push($ticket);
            }

            // ❌ KHÔNG CÒN cập nhật $trip->so_ghe_trong ở đây nữa
        }

        // --------------------------
        // 6. Tạo Payments cho mỗi ticket
        // --------------------------
        foreach ($tickets as $ticket) {
            Payment::factory()->state([
                'ticket_id' => $ticket->id,
                'user_id'   => $ticket->user_id,
            ])->create();
        }

        // --------------------------
        // 7. Tạo Reviews (chỉ user đã mua vé của trip)
        // --------------------------
        foreach ($trips as $trip) {
            $usersBought = $tickets
                ->where('trip_id', $trip->id)
                ->pluck('user_id')
                ->unique();

            foreach ($usersBought as $userId) {
                $numReviews = rand(0, 2);
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
