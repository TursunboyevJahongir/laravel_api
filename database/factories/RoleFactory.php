<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        return [
            'name'       => $this->faker->slug,
            'title'      => [
                'uz' => $this->faker->title,
                'ru' => $this->faker->title,
                'en' => $this->faker->title,
            ],
            'guard_name' => 'api',
        ];
    }
}
