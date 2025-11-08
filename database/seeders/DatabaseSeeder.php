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
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo admin chính với email cố định
        User::create([
            'user_code' => 'DATN_FA25_PoLyCoach_0001',
            'first_name' => 'Admin',
            'last_name' => 'System',
            'full_name' => 'Admin System',
            'email' => 'admin@example.com',
            'phone' => '0123456789',
            'role' => 'admin',
            'status' => 1,
            'password' => Hash::make('123456'),
            'image' => null,
        ]);

        $customers = User::factory()
            ->count(8)
            ->state(['role' => 'customer'])
            ->sequence(fn ($seq) => [
                'user_code' => 'DATN_FA25_PoLyCoach_' . str_pad($seq->index + 6, 4, '0', STR_PAD_LEFT)
            ])
            ->create();
    }
}
