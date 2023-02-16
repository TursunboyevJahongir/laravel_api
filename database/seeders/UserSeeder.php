<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        for ($i = 1; $i <= 5; $i++) {
            for ($j = 1; $j <= 1000; $j++) {
                User::insert(['first_name'      => fake()->firstName(),
                              'last_name'       => fake()->lastName,
                              'phone'           => fake()->phoneNumber,
                              'password'        => fake()->password,
                              'phone_confirmed' => fake()->boolean,
                              'is_active'       => fake()->boolean,
                              'created_at'      => fake()->dateTime,
                              'updated_at'      => fake()->dateTime,
                             ]);
            }
        }
    }
}
