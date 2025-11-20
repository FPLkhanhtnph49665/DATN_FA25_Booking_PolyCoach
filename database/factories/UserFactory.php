<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserFactory extends Factory
{
    protected $model = User::class;

    protected static ?string $password;

    public function definition(): array
    {
        $firstName = $this->faker->firstName();
        $lastName = $this->faker->lastName();

        $number = $this->faker->unique()->numberBetween(1, 9999);
        $userCode = 'DATN_FA25_PoLyCoach_' . str_pad($number, 4, '0', STR_PAD_LEFT);

        return [
            'user_code' => $userCode,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'full_name' => $firstName . ' ' . $lastName,
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->numerify('09########'),
            'role' => $this->faker->randomElement(['admin','user']),
            'status' => 1,
            'password' => static::$password ??= Hash::make('123456'),
            'image' => null,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
