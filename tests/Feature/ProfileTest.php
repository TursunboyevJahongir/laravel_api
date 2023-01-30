<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\CoreTest;

final class ProfileTest extends CoreTest
{
    public function test_profile_get()
    {
        $response = $this->withToken($this->token)->get('/users/profile');

        $response->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json->hasAll(['code',
                                                                    'message',
                                                                    'data',
                                                                    'data.user'])
            );
    }

    public function test_profile_get_with_relations()
    {
        $response = $this->withToken($this->token)->get('/users/profile?relations=avatar;roles:id,name');

        $response->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json->hasAll(['code',
                                                                    'message',
                                                                    'data',
                                                                    'data.user',
                                                                    'data.user.avatar',
                                                                    'data.user.roles'])
            );
    }

    public function test_profile_update()
    {
        $response = $this->withToken($this->token)
            ->patch('/users/profile',
                    [
                        'first_name'            => $this->faker->firstName,
                        'last_name'             => $this->faker->lastName,
                        'phone'                 => '998' . rand(100000000, 999999999),
                        'password'              => $password = $this->faker->password(8) . '1a',
                        'password_confirmation' => $password,
                        'avatar'                => UploadedFile::fake()->image('avatar.jpg'),
                    ]);
        $response->assertStatus(204);
    }
}
