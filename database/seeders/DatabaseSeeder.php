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


    }
}
