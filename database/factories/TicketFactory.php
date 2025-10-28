<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        $trip = Trip::inRandomOrder()->first() ?? Trip::factory()->create();
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $maxSeats = $trip->bus->so_ghe ?? 40;

        return [
            'trip_id' => $trip->id,
            'user_id' => $user->id,
            'so_ghe' => $this->faker->numberBetween(1, $maxSeats),
            'trang_thai' => $this->faker->randomElement(['pending', 'paid', 'canceled']),
            'phuong_thuc_thanh_toan' => $this->faker->randomElement(['Tiền mặt', 'Momo', 'VNPay']),
        ];
    }
}
