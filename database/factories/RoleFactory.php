<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
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
