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
        // Lấy 1 ticket ngẫu nhiên, nếu chưa có thì tạo mới
        $ticket = Ticket::inRandomOrder()->first() ?? Ticket::factory()->create();

        // User thanh toán: lấy từ ticket hoặc tạo mới
        $user = $ticket->user ?? User::inRandomOrder()->first() ?? User::factory()->create();

        return [
            'ticket_id' => $ticket->id,
            'user_id'   => $user->id,
            'amount'    => $this->faker->randomFloat(2, 100000, 500000), // thay so_tien -> amount
            'method'    => $this->faker->randomElement(['Cash','Momo','VNPay']), // thay phuong_thuc -> method
            'status'    => $this->faker->randomElement(['pending','success','failed']), // thay trang_thai -> status
        ];
    }
}
