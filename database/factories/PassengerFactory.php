<?php

namespace Database\Factories;

use App\Models\Passenger;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

class PassengerFactory extends Factory
{
    protected $model = Passenger::class;

    public function definition(): array
{
    $ticket = Ticket::inRandomOrder()->first() ?? Ticket::factory()->create();
    $totalSeats = $ticket->trip->bus->so_ghe ?? 40;
    $bookedSeats = $ticket->passengers->pluck('seat_number')->toArray() ?? [];
    $availableSeats = array_diff(range(1, $totalSeats), $bookedSeats);

    return [
        'ticket_id' => $ticket->id,
        'name' => $this->faker->name(),
        'phone' => $this->faker->phoneNumber(),
        'age' => $this->faker->numberBetween(1, 70),
        'seat_number' => $this->faker->randomElement($availableSeats),
    ];
}

}
