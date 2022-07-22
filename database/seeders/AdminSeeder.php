<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'phone' => '998999999999',
            'password' => Hash::make('111111'),
            'phone_confirmed' => 1,
            'is_active' => 1,
        ])->assignRole('superadmin');
    }
}
