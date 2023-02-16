<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run():void
    {
        $this->call(RolesTableSeeder::class);
        $this->call(AdminSeeder::class);
        \App\Models\User::factory(2)->create();
        \App\Models\User::factory(2)->create();
        \App\Models\User::factory(4)->create();
        \App\Models\User::factory(4)->create();
        \App\Models\Category::factory(3)->create();
        \App\Models\Product::factory(10)->create();
    }
}
