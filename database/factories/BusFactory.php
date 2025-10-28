<?php

namespace Database\Factories;

use App\Models\Bus;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusFactory extends Factory
{
    protected $model = Bus::class;

    public function definition(): array
    {
        return [
            'bien_so' => strtoupper($this->faker->bothify('XX-####')),
            'so_ghe' => $this->faker->numberBetween(20, 50),
            'loai_xe' => $this->faker->randomElement(['Ghế ngồi','Giường nằm']),
            'trang_thai' => 1,
        ];
    }
}
