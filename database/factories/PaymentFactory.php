<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $ticket = Ticket::inRandomOrder()->first() ?? Ticket::factory()->create();
        $user = $ticket->user ?? User::inRandomOrder()->first() ?? User::factory()->create();

        return [
            'ticket_id' => $ticket->id,
            'user_id' => $user->id, // khách hàng thanh toán
            'so_tien' => $this->faker->randomFloat(2, 100000, 500000),
            'phuong_thuc' => $this->faker->randomElement(['Tiền mặt','Momo','VNPay']),
            'trang_thai' => $this->faker->randomElement(['pending','success','failed']),
        ];
    }
}
