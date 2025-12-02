<?php

namespace Database\Factories;

use App\Models\Route;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

class RouteFactory extends Factory
{
    protected $model = Route::class;

    public function definition(): array
    {
        // Lấy tất cả city
        $cities = City::all();

        // Nếu chưa có city nào, throw exception
        if ($cities->count() < 2) {
            throw new \Exception('Cần ít nhất 2 city để tạo route.');
        }

        // Lấy 2 city khác nhau
        $fromCity = $cities->random();
        do {
            $toCity = $cities->random();
        } while ($toCity->id === $fromCity->id);

        return [
            'from_city_id' => $fromCity->id,
            'to_city_id'   => $toCity->id,
            'distance'  => $this->faker->numberBetween(50, 500), // km
            'estimated_time' => $this->faker->numberBetween(1, 10), // giờ
            'status'   => $this->faker->randomElement([0, 1]), // 0: inactive, 1: active
        ];
    }
}
