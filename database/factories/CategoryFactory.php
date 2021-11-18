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
        return $this->afterCreating(static function (Category $intro) use ($fake) {
            @mkdir(public_path('/uploads/category/'), 0777, true);
            $time = time() . random_int(1000, 60000);
            copy($fake->imageUrl(), public_path('/uploads/category/') . $time . '.jpg');
            $path = '/uploads/category/' . $time . '.jpg';
            $intro->ico()->create([
                'name' => $fake->word(),
                'type' => $fake->fileExtension,
                'full_url' => $path,
                'additional_identifier' => Category::CATEGORY_RESOURCES,
            ]);
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
            'title' => $this->faker->unique()->word,
            'position' => $this->faker->boolean ? $this->faker->numberBetween(0, 150) : null
        ];
    }
}
