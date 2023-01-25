<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\CoreTest;

final class AuthTest extends CoreTest
{
    use WithFaker;

    public function test_register()
    {
        $response = $this->post('/auth/register',
                                [
                                    'first_name'            => $this->faker->firstName,
                                    'last_name'             => $this->faker->lastName,
                                    'phone'                 => $phone = '998' . rand(100000000, 999999999),
                                    'password'              => $pass = $this->faker->password(8) . '1a',
                                    'password_confirmation' => $pass,
                                    'roles'                 => ['customer'],
                                ]);

        $response->assertStatus(201)
            ->assertJson(fn(AssertableJson $json) => $json->hasAll(['code',
                                                                    'message',
                                                                    'data',
                                                                    'data.result.token',
                                                                    'data.result.refresh_token',
                                                                    'data.result.user'])
            );

        $this->deleteUser($phone);
    }

    public function test_login()
    {
        [$phone, $token, $refresh, $pass] = $this->createUser();
        $response = $this->post('/auth/login',
                                [
                                    'phone'    => $phone,
                                    'password' => $pass,
                                ]);
        $response->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json->hasAll(['code',
                                                                    'message',
                                                                    'data',
                                                                    'data.result.token',
                                                                    'data.result.refresh_token',
                                                                    'data.result.user'])
            );
        $this->deleteUser($phone);
    }

    public function test_refresh_token()
    {
        [$phone, $token, $refresh_token] = $this->createUser();
        $response = $this->withToken($refresh_token)->postJson('/auth/refresh');
        $response->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json->hasAll(['code',
                                                                    'message',
                                                                    'data',
                                                                    'data.result.token',
                                                                    'data.result.refresh_token',
                                                                    'data.result.user'])
            );
        $this->deleteUser($phone);
    }

    public function test_logout()
    {
        [$phone, $token] = $this->createUser();
        $response = $this->withToken($token)->post('/auth/logout');
        $response->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json->hasAll(['code',
                                                                    'message',
                                                                    'data']));
        $this->deleteUser($phone);
    }
}
