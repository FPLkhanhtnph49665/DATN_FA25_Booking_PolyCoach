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
    $startDate = $this->faker->dateTimeBetween('+1 days', '+7 days'); // ngày khởi hành
    $durationHours = rand(1, 6); // thời gian di chuyển

    return [
        'route_id' => Route::inRandomOrder()->first()->id ?? Route::factory(),
        'bus_id'   => Bus::inRandomOrder()->first()->id ?? Bus::factory(),
        'ngay_khoi_hanh' => $startDate,
        'gio_khoi_hanh' => $startDate,
        'ngay_den' => (clone $startDate)->modify("+{$durationHours} hours"),
        'gio_den'  => (clone $startDate)->modify("+{$durationHours} hours"),
        'gia_ve'   => $this->faker->numberBetween(100000, 500000),
        'trang_thai' => 1,
    ];
}
}
