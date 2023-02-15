<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    //https://laravel.com/docs/seeding#muting-model-events
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->activated()
            ->create(['phone'    => '998999999999',
                      'password' => '111111',
                     ])->assignRole('superadmin');
    }
}
