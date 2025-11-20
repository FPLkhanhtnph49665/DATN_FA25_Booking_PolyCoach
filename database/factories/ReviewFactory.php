<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use App\Models\Trip;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $trip = Trip::inRandomOrder()->first() ?? Trip::factory()->create();

        return [
            'user_id'  => $user->id,
            'trip_id'  => $trip->id,
            'rating'   => $this->faker->numberBetween(1,5),
            'content'  => $this->faker->paragraph(),
            'status'   => $this->faker->randomElement(['pending','approved','rejected']),
        ];
    }
}
