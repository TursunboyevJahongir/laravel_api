<?php

namespace Database\Factories;

use App\Models\Category;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * @throws Exception
     */
    public function configure()
    {
        $fake = $this->faker;

        return $this->afterCreating(static function (Category $model) use ($fake) {
            @mkdir(storage_path("/app/public/uploads/" . $model->getFilePath() . "/original"), 0777, true);
            @mkdir(storage_path("/app/public/uploads/" . $model->getFilePath() . "/1024"), 0777, true);
            @mkdir(storage_path("/app/public/uploads/" . $model->getFilePath() . "/512"), 0777, true);
            $time = time() . rand(1000, 60000);
            copy(public_path('faker/' . rand(1, 4) . '.jpg'), storage_path("app/public/uploads/" . $model->getFilePath() . "/original/") . $time . '.jpg');
            copy(public_path('faker/' . rand(1, 4) . '.jpg'), storage_path("app/public/uploads/" . $model->getFilePath() . "/1024/") . $time . '.jpg');
            copy(public_path('faker/' . rand(1, 4) . '.jpg'), storage_path("app/public/uploads/" . $model->getFilePath() . "/512/") . $time . '.jpg');
            $model->ico()->create(['type'          => 'jpg',
                                   'path_original' => "/uploads/" . $model->getFilePath() . "/original/$time.jpg",
                                   'path_1024'     => "/uploads/" . $model->getFilePath() . "/1024/$time.jpg",
                                   'path_512'      => "/uploads/" . $model->getFilePath() . "/512/$time.jpg"]);
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
            'parent_id'   => $this->faker->boolean ? null : Category::inRandomOrder()->first()?->id,
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
            'position'    => $this->faker->numberBetween(0, 200),
        ];
    }
}
