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
        return [
            'user_id' => User::factory(),
            'trip_id' => Trip::factory(),
            'rating' => $this->faker->numberBetween(1,5),
            'noi_dung' => $this->faker->sentence(),
        ];
    }
}
