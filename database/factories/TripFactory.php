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
            'ngay_khoi_hanh' => $startDate->format('Y-m-d'),
            'gio_khoi_hanh' => $startDate->format('H:i:s'),
            'ngay_den' => $endDate->format('Y-m-d'),
            'gio_den' => $endDate->format('H:i:s'),
            'gia_ve' => $this->faker->numberBetween(100000, 500000),
            'trang_thai' => 1,
        ];
    }
}
