<?php

namespace Database\Factories;

use App\Models\Route;
use Illuminate\Database\Eloquent\Factories\Factory;

class RouteFactory extends Factory
{
    protected $model = Route::class;

    public function definition(): array
    {
        return [
            'diem_di' => $this->faker->city(),
            'diem_den' => $this->faker->city(),
            'quang_duong' => $this->faker->numberBetween(50, 500),
            'thoi_gian_du_kien' => $this->faker->time('H:i:s'),
            'trang_thai' => 1,
        ];
    }
}
