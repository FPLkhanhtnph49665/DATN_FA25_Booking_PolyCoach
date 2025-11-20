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
        $maxSeats = $trip->bus?->seat_count ?? 40;

        return [
            'trip_id' => $trip->id,
            'user_id' => $user->id,
            'seat_number' => $this->faker->numberBetween(1, $maxSeats), // thay so_ghe -> seat_number
            'status' => $this->faker->randomElement(['pending', 'paid', 'canceled']), // thay trang_thai -> status
            'payment_method' => $this->faker->randomElement(['Cash', 'Momo', 'VNPay']), // phuong_thuc_thanh_toan -> payment_method
        ];
    }
}
