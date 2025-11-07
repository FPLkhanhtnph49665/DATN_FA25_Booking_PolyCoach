<?php

namespace Database\Factories;

use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    public function definition(): array
    {
        // Lấy ngẫu nhiên 1 trip và customer nếu đã có trong DB
        $trip = Trip::inRandomOrder()->first() ?? Trip::factory()->create();
        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        $paymentMethods = ['Tiền mặt', 'MoMo', 'ZaloPay', 'VNPay'];
        $statuses = ['Đang chờ', 'Đã thanh toán', 'Đã hủy'];

        return [
            'trip_id' => $trip->id,
            'user_id' => $user->id,
            'seat_number' => fake()->numberBetween(1, 40),
            'payment_method' => fake()->randomElement($paymentMethods),
            'total_price' => fake()->numberBetween(50000, 500000),
            'status' => fake()->randomElement($statuses),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
