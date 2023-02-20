<?php

namespace App\Core\Test\Feature;

use App\Models\User;
use Arr;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

abstract class CoreTest extends TestCase
{
    use WithFaker;

    //use RefreshDatabase;
    public User         $user;
    public string       $phone;
    public string       $pass;
    public string|array $roles = 'customer';

    protected function setUp(): void
    {
        parent::setUp();
        //dd(\DB::connection()->getDatabaseName());
        //$this->seed(TestSeeder::class);

        [$this->user, $this->phone, $this->pass] = $this->createUser($this->roles);
    }

    protected function tearDown(): void
    {
        $this->deleteUser($this->user);
        parent::tearDown();
    }

    public function createUser(string|array $roles = null)
    {
        $roles = $roles ?? 'customer';
        $user  = User::factory()
            ->create(['phone'     => $phone = '998' . rand(100000000, 999999999),
                      'password'  => $password = $this->faker->password(8) . '1a',
                      'is_active' => 1,
                     ])->assignRole(Arr::wrap($roles));


        return [$user, $phone, $password];
    }

    public function deleteUser(User $user)
    {
        $user->forceDelete();
    }
}
