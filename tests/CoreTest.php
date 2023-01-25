<?php

namespace Tests;

use App\Models\User;
use Arr;
use Illuminate\Foundation\Testing\WithFaker;

abstract class CoreTest extends TestCase
{
    use WithFaker;

    public string       $phone;
    public string       $pass;
    public string       $token;
    public string       $refreshToken;
    public string|array $roles = 'customer';

    protected function setUp(): void
    {
        parent::setUp();
        [$this->phone, $this->token, $this->refreshToken, $this->pass] = $this->createUser();
    }

    protected function tearDown(): void
    {
        $this->deleteUser($this->phone);
        parent::tearDown();
    }

    public function createUser(string|array $roles = null)
    {
        $roles = $this->roles ?? 'customer';
        //User::factory()->create([
        //                            'phone'    => $phone,
        //                            'password' => $password,
        //                        ]);

        $this->post('/auth/register',
                    [
                        'first_name'            => $this->faker->firstName,
                        'last_name'             => $this->faker->lastName,
                        'phone'                 => $phone = '998' . rand(100000000, 999999999),
                        'password'              => $password = $this->faker->password(8) . '1a',
                        'password_confirmation' => $password,
                        'roles'                 => Arr::wrap($roles),
                    ]);

        $response = $this->post('/auth/login',
                                [
                                    'phone'    => $phone,
                                    'password' => $password,
                                ]);

        return [$phone,
                $response->getData()->data->result->token,
                $response->getData()->data->result->refresh_token,
                $password];
    }

    public function deleteUser($phone)
    {
        if ($user = User::firstWhere('phone', $phone)) {
            $user->forceDelete();
        }
    }
}
