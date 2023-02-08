<?php

namespace Tests\Feature\Core;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Feature\Interface\ResourceInterface;

abstract class ResourceTest extends CoreTest implements ResourceInterface
{
    public string|array $roles     = 'superadmin';
    public array        $relations = [];
    public array        $appends   = [];

    abstract public function getRouteName(): string;

    abstract public function getModel(): Model;

    public function testIndexNotAccess()
    {
        $role = Role::factory()->create();
        [$user] = $this->createUser($role->name);
        $response = $this->actingAs($user)->get("/{$this->getRouteName()}");
        $response->assertStatus(403)
            ->assertJson(fn(AssertableJson $json) => $json->hasAll(['code',
                                                                    'message',
                                                                    'data'])
            );

        $role->forceDelete();
        $user->forceDelete();
    }

    public function testIndexPagination()
    {
        $response = $this->be($this->user)
            ->get("/{$this->getRouteName()}?relations=" . implode(';', $this->relations));
        $response->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json->hasAll(['code',
                                                                    'message',
                                                                    'data',
                                                                    'data.' . $this->getRouteName()])
            );
    }

    public function testIndexCollection()
    {
        $response = $this->be($this->user)->get("/{$this->getRouteName()}?getBy=collection&relations=" .
                                                implode(';', $this->relations) .
                                                '&appends=' . implode(';', $this->appends));
        $this->assertAuthenticated();
        $response->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json->hasAll(['code',
                                                                    'message',
                                                                    'data',
                                                                    'data.' . $this->getRouteName()])
            );
    }

    public function testGetOne()
    {
        /** @var Model $model */
        $model = $this->getModel()::factory()->create();

        $response = $this->actingAs($this->user)
            ->get("/{$this->getRouteName()}/{$model->{$model->getKeyName()}}?relations=" .
                  implode(';', $this->relations) .
                  '&appends=' . implode(';', $this->appends));
        $response->assertStatus(200)
            ->assertJson(fn(AssertableJson $json) => $json->hasAll(['code',
                                                                    'message',
                                                                    'data'])
            );

        $model->forceDelete();
    }

    public function testGetOneNotAccess()
    {
        $role = Role::factory()->create();
        [$user] = $this->createUser($role->name);
        $model    = $this->getModel()::factory()->create();
        $response = $this->actingAs($user)->get("/{$this->getRouteName()}/{$model->{$model->getKeyName()}}");
        $response->assertStatus(403)
            ->assertJson(fn(AssertableJson $json) => $json->hasAll(['code',
                                                                    'message',
                                                                    'data'])
            );

        $role->forceDelete();
        $this->deleteUser($user);
    }

    abstract public function testStore();

    public function testStoreNotAccess()
    {
        $role = Role::factory()->create();
        [$user] = $this->createUser($role->name);
        $response = $this->actingAs($user)->post("/{$this->getRouteName()}", []);

        $response->assertStatus(403)
            ->assertJson(fn(AssertableJson $json) => $json->hasAll(['code',
                                                                    'message',
                                                                    'data'])
            );

        $this->deleteUser($user);
    }

    abstract public function testUpdate();

    public function testUpdateNotAccess()
    {
        $role = Role::factory()->create();
        [$user] = $this->createUser($role->name);
        $model = User::factory()->create();

        $response = $this->actingAs($user)->patch("/{$this->getRouteName()}/{$model->id}", []);

        $response->assertStatus(403)
            ->assertJson(fn(AssertableJson $json) => $json->hasAll(['code',
                                                                    'message',
                                                                    'data'])
            );

        $role->forceDelete();
        $model->forceDelete();
        $this->deleteUser($user);
    }

    public function testDestroy()
    {
        /** @var Model $model */
        $model    = $this->getModel()::factory()->create();
        $response = $this->actingAs($this->user)->delete("/{$this->getRouteName()}/{$model->{$model->getKeyName()}}");
        $response->assertStatus(204);

        $model->forceDelete();
    }

    public function testDestroyNotAccess()
    {
        $role = Role::factory()->create();
        [$user] = $this->createUser($role->name);

        /** @var Model $model */
        $model    = $this->getModel()::factory()->create();
        $response = $this->actingAs($user)->delete("/{$this->getRouteName()}/{$model->{$model->getKeyName()}}");
        $response->assertStatus(403);

        $role->forceDelete();
        $model->forceDelete();
        $this->deleteUser($user);
    }
}
