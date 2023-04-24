<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'      => $this->faker->name,
            'is_active' => $this->faker->boolean,
            'position'  => $this->faker->numberBetween(0, 200),
            "author_id" => User::inRandomOrder()->value('id'),
        ];
    }
}
