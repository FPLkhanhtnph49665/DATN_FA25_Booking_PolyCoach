<?php

namespace Database\Factories;

use App\Models\Route;
use Illuminate\Database\Eloquent\Factories\Factory;

class RouteFactory extends Factory
{
    protected $model = Route::class;

    public function definition(): array
    {
        $diem_di = $this->faker->city();
        $diem_den = $this->faker->city();
        while($diem_den === $diem_di){
            $diem_den = $this->faker->city();
        }

        return [
            'diem_di' => $diem_di,
            'diem_den' => $diem_den,
            'quang_duong' => $this->faker->numberBetween(50, 500),
            'thoi_gian_du_kien' => gmdate('H:i:s', $this->faker->numberBetween(3600, 36000)), // 1–10 giờ
            'trang_thai' => 1,
        ];
    }
}
