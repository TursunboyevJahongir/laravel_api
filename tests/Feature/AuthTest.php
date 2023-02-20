<?php

namespace Tests\Feature;

use App\Core\Test\Feature\CoreTest;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;

final class AuthTest extends CoreTest
{
    public function testRegister()
    {
        $response = $this->post('/auth/register',
                                [
                                    'first_name'            => $this->faker->firstName,
                                    'last_name'             => $this->faker->lastName,
                                    'phone'                 => $phone = '998' . rand(100000000, 999999999),
                                    'password'              => $pass = $this->faker->password(8) . '1a',
                                    'password_confirmation' => $pass,
                                    'roles'                 => ['customer'],
                                    'avatar'                => UploadedFile::fake()->image('avatar.jpg'),
                                ]);

        $response->assertStatus(201)
            ->assertJson(fn(AssertableJson $json) => $json->hasAll(['code',
                                                                    'message',
                                                                    'data',
                                                                    'data.result.token',
                                                                    'data.result.refresh_token',
                                                                    'data.result.user.avatar'])
            );

        \Storage::disk('public')->assertExists($response->getData()->data->result->user->avatar->path_original);
        \Storage::disk('public')->assertExists($response->getData()->data->result->user->avatar->path_1024);
        \Storage::disk('public')->assertExists($response->getData()->data->result->user->avatar->path_512);

        User::firstWhere('phone', $phone)->forceDelete();
    }

    public function testLogin()
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

    public function testRefreshToken()
    {
        $response = $this->post('/auth/login',
                                [
                                    'phone'    => $this->phone,
                                    'password' => $this->pass,
                                ]);

        $response = $this->withToken($response->getData()->data->result->refresh_token)->postJson('/auth/refresh');
        $response->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json->hasAll(['code',
                                                                    'message',
                                                                    'data',
                                                                    'data.result.token',
                                                                    'data.result.refresh_token',
                                                                    'data.result.user'])
            );
    }

    public function testRefreshTokenFail()
    {
        $response = $this->post('/auth/login',
                                [
                                    'phone'    => $this->phone,
                                    'password' => $this->pass,
                                ]);

        $this->withToken($response->getData()->data->result->refresh_token)->postJson('/auth/refresh');

        $response = $this->withToken($response->getData()->data->result->refresh_token)->postJson('/auth/refresh');
        $response->assertStatus(401)
            ->assertJson(fn(AssertableJson $json) => $json->hasAll(['code',
                                                                    'message',
                                                                    'data'])
            );
    }

    public function testLogout()
    {
        $response = $this->post('/auth/login',
                                [
                                    'phone'    => $this->phone,
                                    'password' => $this->pass,
                                ]);

        $response = $this->withToken($response->getData()->data->result->token)->post('/auth/logout');
        $response->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json->hasAll(['code',
                                                                    'message',
                                                                    'data']));
    }
}
