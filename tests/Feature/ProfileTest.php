<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\FeatureCore\Core\CoreTest;

final class ProfileTest extends CoreTest
{
    public function testProfileGet()
    {
        $response = $this->actingAs($this->user)->get('/users/profile');

        $response->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json->hasAll(['code',
                                                                    'message',
                                                                    'data',
                                                                    'data.user'])
            );
    }

    public function testProfileGetWithRelations()
    {
        $response = $this->actingAs($this->user)->get('/users/profile?relations=avatar;roles:id,name');

        $response->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json->hasAll(['code',
                                                                    'message',
                                                                    'data',
                                                                    'data.user',
                                                                    'data.user.avatar',
                                                                    'data.user.roles'])
            );
    }

    public function testProfileUpdate()
    {
        $response = $this->actingAs($this->user)
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
