<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\Feature\Core\ResourceTest;

final class CategoriesTest extends ResourceTest
{
    public array $relations = ['ico', 'products', 'children', 'parent'];
    public array $appends   = ['sub_description'];

    public function getRouteName(): string
    {
        return 'categories';
    }

    public function getModel(): Model
    {
        return new Category();
    }

    public function testStore()
    {
        $response = $this->actingAs($this->user)
            ->post("/categories",
                   [
                       'parent_id'   => $this->faker->boolean ? Category::inRandomOrder()->first()?->id : null,
                       'name'        => [
                           'uz' => $this->faker->word . ' ' . $this->faker->word,
                           'ru' => $this->faker->word . ' ' . $this->faker->word,
                           'en' => $this->faker->word . ' ' . $this->faker->word,
                       ],
                       'description' => [
                           'uz' => $this->faker->text(200),
                           'ru' => $this->faker->text(200),
                           'en' => $this->faker->text(200),
                       ],
                       'is_active'   => $this->faker->boolean,
                       'position'    => $this->faker->numberBetween(0, 200),
                       'ico'         => UploadedFile::fake()->image('ico.jpg'),
                   ]);

        $response->assertStatus(201)
            ->assertJson(fn(AssertableJson $json) => $json->hasAll(['code',
                                                                    'message',
                                                                    'data',
                                                                    'data.category',
                                                                    'data.category.ico'])
            );

        Category::find($response->getData()->data->category->id)->forceDelete();
    }

    public function testStoreNameUnique()
    {
        $face = Category::factory()
            ->create(['name' => $categoryName = [
                'uz' => $this->faker->word . ' ' . $this->faker->word,
                'ru' => $this->faker->word . ' ' . $this->faker->word,
                'en' => $this->faker->word . ' ' . $this->faker->word,
            ]]);

        $response = $this->actingAs($this->user)
            ->post("/categories",
                   [
                       'name' => $categoryName,
                   ]);

        $response->assertStatus(422);

        $face->forceDelete();
    }

    public function testUpdate()
    {
        $model = Category::factory()->create();

        $response = $this->actingAs($this->user)
            ->patch("/categories/$model->id",
                    [
                        'parent_id'   => $this->faker->boolean ? Category::inRandomOrder()->first()?->id : null,
                        'name'        => [
                            'uz' => $this->faker->word . ' ' . $this->faker->word,
                            'ru' => $this->faker->word . ' ' . $this->faker->word,
                            'en' => $this->faker->word . ' ' . $this->faker->word,
                        ],
                        'description' => [
                            'uz' => $this->faker->text(200),
                            'ru' => $this->faker->text(200),
                            'en' => $this->faker->text(200),
                        ],
                        'is_active'   => $this->faker->boolean,
                        'position'    => $this->faker->numberBetween(0, 200),
                        'ico'         => UploadedFile::fake()->image('ico.jpg'),
                    ]);

        $response->assertStatus(204);

        $model->forceDelete();
    }
}
