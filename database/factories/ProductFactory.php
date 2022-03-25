<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * @throws Exception
     */
    public function configure()
    {
        $fake = $this->faker;
        return $this->afterCreating(static function (Product $product) use ($fake) {
            @mkdir(public_path('/uploads/products/'), 0777, true);
            $time = time() . random_int(1000, 60000);
            copy($fake->imageUrl(), public_path('/uploads/products/') . $time . '.jpg');
            $path = '/uploads/products/' . $time . '.jpg';
            $product->mainImage()->create([
                'name' => $fake->word(),
                'type' => $fake->fileExtension,
                'full_url' => $path,
                'additional_identifier' => Product::PRODUCT_MAIN_IMAGE_RESOURCES,
            ]);


            @mkdir(public_path('/uploads/products/'), 0777, true);
            $size = random_int(3, 7);
            for ($i = 0; $i <= $size; $i++) {
                $time = time() . random_int(1000, 60000);
                copy($fake->imageUrl(), public_path("/uploads/products/$time.jpg"));
                $path = "/uploads/products/$time.jpg";
                $product->images()->create([
                    'name' => $fake->word(),
                    'type' => $fake->fileExtension,
                    'full_url' => $path,
                    'additional_identifier' => Product::PRODUCT_IMAGES_RESOURCES,
                ]);
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
            'author_id' => User::all()->random()->id,
            'category_id' => Category::all()->random()->id,
            'title' => $this->faker->word,
            'description' => $this->faker->text(200),
            'price' => $this->faker->numberBetween(10000, 500000),
            'position' => $this->faker->boolean ? $this->faker->numberBetween(0, 150) : null,
            'tag' => $this->faker->randomElement(['sport,tennis', 'sport,basketball', 'boy', 'boy,girl', 'girl', 'mather', 'father', 'family', 'book', 'book,pencil', 'book,pen']),
        ];
    }
}
