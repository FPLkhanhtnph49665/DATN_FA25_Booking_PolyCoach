<?php

namespace Database\Factories;

use App\Models\Trip;
use App\Models\Route;
use App\Models\Bus;
use Illuminate\Database\Eloquent\Factories\Factory;

class TripFactory extends Factory
{
    protected $model = Trip::class;

    public function definition(): array
    {
        $route = Route::inRandomOrder()->first() ?? Route::factory()->create();
        $bus = Bus::inRandomOrder()->first() ?? Bus::factory()->create();

        $startDate = $this->faker->dateTimeBetween('+1 days', '+7 days');
        $durationHours = rand(1, 6);

        $endDate = (clone $startDate)->modify("+{$durationHours} hours");

        return [
            'route_id' => $route->id,
            'bus_id' => $bus->id,
            'departure_date' => $startDate->format('Y-m-d'),
            'departure_time' => $startDate->format('H:i:s'),
            'arrival_date' => $endDate->format('Y-m-d'),
            'arrival_time' => $endDate->format('H:i:s'),
            'ticket_price' => $this->faker->numberBetween(100000, 500000),
            'status' => 1, // 1 = active
            'trip_code' => strtoupper($this->faker->bothify('TRIP-#####')),
        ];
    }
}
