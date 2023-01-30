<?php

namespace Tests\Feature;

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
        $response = $this->post('/auth/login',
                                [
                                    'phone'    => $this->phone,
                                    'password' => $this->pass,
                                ]);
        $response->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json->hasAll(['code',
                                                                    'message',
                                                                    'data',
                                                                    'data.result.token',
                                                                    'data.result.refresh_token',
                                                                    'data.result.user'])
            );
    }

    public function test_refresh_token()
    {
        $response = $this->withToken($this->refreshToken)->postJson('/auth/refresh');
        $response->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json->hasAll(['code',
                                                                    'message',
                                                                    'data',
                                                                    'data.result.token',
                                                                    'data.result.refresh_token',
                                                                    'data.result.user'])
            );
    }

    public function test_refresh_token_fail()
    {
        $this->withToken($this->refreshToken)->postJson('/auth/refresh');

        $response = $this->withToken($this->refreshToken)->postJson('/auth/refresh');
        $response->assertStatus(401)
            ->assertJson(fn(AssertableJson $json) => $json->hasAll(['code',
                                                                    'message',
                                                                    'data'])
            );
    }

    public function test_logout()
    {
        $response = $this->withToken($this->token)->post('/auth/logout');
        $response->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json->hasAll(['code',
                                                                    'message',
                                                                    'data']));
    }
}
