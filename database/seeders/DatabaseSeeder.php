<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(AdminSeeder::class);
//        \App\Models\Category::factory(10)->create();
        \App\Models\User::factory(2)->create();
        \App\Models\User::factory(2)->create();
        \App\Models\User::factory(4)->create();
        \App\Models\User::factory(4)->create();
        \App\Models\Category::factory(15)->create();
        \App\Models\Product::factory(150)->create();
        $this->call(UserSeeder::class);
    }
}
