<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
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

        dump('create active user');
        User::factory(2)->activated()->create();
        User::factory(5)->createQuietly();

        dump('create Category with products');
        Category::factory(2)->hasProducts(random_int(5, 10))->create();
        Category::factory(5)->hasProducts(random_int(5, 10))->create();
        Category::factory(5)->hasProducts(random_int(5, 10))->create();
    }
}
