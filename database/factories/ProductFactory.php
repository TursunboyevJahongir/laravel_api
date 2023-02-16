<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function configure()
    {
        $fake = $this->faker;

        return $this->afterCreating(static function (Product $model) use ($fake) {
            @mkdir(storage_path("/app/public/uploads/" . $model->getFilePath() . "/original"), 0777, true);
            @mkdir(storage_path("/app/public/uploads/" . $model->getFilePath() . "/1024"), 0777, true);
            @mkdir(storage_path("/app/public/uploads/" . $model->getFilePath() . "/512"), 0777, true);
            $time = time() . rand(1000, 60000);
            copy(public_path('images/faker/' . rand(1, 6) . '.jpg'),
                 storage_path("app/public/uploads/" . $model->getFilePath() . "/original/$time.jpg"));
            copy(public_path('images/faker/' . rand(1, 6) . '.jpg'),
                 storage_path("app/public/uploads/" . $model->getFilePath() . "/1024/$time.jpg"));
            copy(public_path('images/faker/' . rand(1, 6) . '.jpg'),
                 storage_path("app/public/uploads/" . $model->getFilePath() . "/512/$time.jpg"));
            $model->mainImage()->create(['type'                  => 'jpg',
                                         'path_original'         => "/uploads/" . $model->getFilePath() . "/original/$time.jpg",
                                         'path_1024'             => "/uploads/" . $model->getFilePath() . "/1024/$time.jpg",
                                         'path_512'              => "/uploads/" . $model->getFilePath() . "/512/$time.jpg",
                                         'additional_identifier' => Product::MAIN_IMAGE,]);

            $size = random_int(1, 3);
            for ($i = 0; $i <= $size; $i++) {
                $time = time() . rand(1000, 60000);
                copy(public_path('images/faker/' . rand(1, 6) . '.jpg'),
                     storage_path("app/public/uploads/" . $model->getFilePath() . "/original/$time.jpg"));
                copy(public_path('images/faker/' . rand(1, 6) . '.jpg'),
                     storage_path("app/public/uploads/" . $model->getFilePath() . "/1024/$time.jpg"));
                copy(public_path('images/faker/' . rand(1, 6) . '.jpg'),
                     storage_path("app/public/uploads/" . $model->getFilePath() . "/512/$time.jpg"));
                $model->images()->create(['type'                  => 'jpg',
                                          'path_original'         => "/uploads/" . $model->getFilePath() . "/original/$time.jpg",
                                          'path_1024'             => "/uploads/" . $model->getFilePath() . "/1024/$time.jpg",
                                          'path_512'              => "/uploads/" . $model->getFilePath() . "/512/$time.jpg",
                                          'additional_identifier' => Product::IMAGES]);
            }
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'author_id'   => User::inRandomOrder()->value('id'),
            'category_id' => Category::inRandomOrder()->value('id'),
            'name'        => [
                'uz' => $this->faker->word,
                'ru' => $this->faker->word,
                'en' => $this->faker->word,
            ],
            'description' => [
                'uz' => $this->faker->text(200),
                'ru' => $this->faker->text(200),
                'en' => $this->faker->text(200),
            ],
            'is_active'   => $this->faker->boolean,
            'position'    => $this->faker->numberBetween(0, 150),
        ];
    }
}
