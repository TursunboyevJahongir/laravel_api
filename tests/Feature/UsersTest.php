<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Feature\Core\ResourceTest;

final class UsersTest extends ResourceTest
{
    public array $relations = ['roles', 'author', 'avatar'];
    public function getRouteName(): string
    {
        return 'users';
    }

    public function getModel(): Model
    {
        return new User();
    }

    public function testStore()
    {
        $role = Role::factory()->create();

        $response = $this->actingAs($this->user)
            ->post("/users",
                   [
                       'first_name'            => $this->faker->firstName,
                       'last_name'             => $this->faker->lastName,
                       'phone'                 => $phone = '998' . rand(100000000, 999999999),
                       'password'              => $password = $this->faker->password(8) . '1a',
                       'password_confirmation' => $password,
                       'roles'                 => [$role->name],
                       'avatar'                => UploadedFile::fake()->image('avatar.jpg'),
                   ]);

        $response->assertStatus(201)
            ->assertJson(fn(AssertableJson $json) => $json->hasAll(['code',
                                                                    'message',
                                                                    'data',
                                                                    'data.user',
                                                                    'data.user.roles',
                                                                    'data.user.avatar'])
            );

        $role->forceDelete();
        User::firstWhere('phone', $phone)->forceDelete();
    }

    public function testUpdate()
    {
        $role = Role::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($this->user)
            ->patch("/users/{$user->id}",
                    [
                        'first_name'            => $this->faker->firstName,
                        'last_name'             => $this->faker->lastName,
                        'phone'                 => $phone = '998' . rand(100000000, 999999999),
                        'password'              => $password = $this->faker->password(8) . '1a',
                        'password_confirmation' => $password,
                        'roles'                 => [$role->name],
                        'avatar'                => UploadedFile::fake()->image('avatar.jpg'),
                    ]);

        $response->assertStatus(204);

        $user->forceDelete();
        $role->forceDelete();
    }
}
