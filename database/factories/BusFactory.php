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
            'plate_number' => strtoupper($this->faker->unique()->bothify('30K-#####')), // thay bien_so
            'seat_count'    => $this->faker->numberBetween(20, 50),                       // thay so_ghe
            'type'          => $this->faker->randomElement(['Seat', 'Sleeper', 'Limousine']), // thay loai_xe
            'status'        => $this->faker->randomElement([0,1]),                        // thay trang_thai
        ];
    }
}
