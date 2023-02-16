<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * @throws \Exception
     * @source https://laravel.com/docs/9.x/eloquent-factories#factory-callbacks
     */
    public function configure()
    {
        return $this->afterCreating(static function (User $user) {
            $user->assignRole('customer');
        });
    }

    public function definition(): array
    {
        return [
            'first_name'         => $this->faker->firstName(),
            'last_name'          => $this->faker->lastName(),
            'phone'              => '998' . $this->faker->numberBetween('100000000', '999999999'),
            'password'           => bcrypt(12345678),
            'is_active'          => $this->faker->boolean(),
            'phone_confirmed'    => $this->faker->boolean(),
            'phone_confirmed_at' => Carbon::now(),

            'author_id' => User::query()->inRandomOrder()->first()?->id,
        ];
    }

    public function activated(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active'          => true,
                'phone_confirmed'    => true,
                'phone_confirmed_at' => now(),
            ];
        });
    }

    public function inactive(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active'          => false,
                'phone_confirmed'    => false,
                'phone_confirmed_at' => null,
            ];
        });
    }
}
