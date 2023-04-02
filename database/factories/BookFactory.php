<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function configure()
    {
        return $this->afterCreating(static function (Book $model) {
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
            $model->image()->create(['type'          => 'jpg',
                                     'path_original' => "/uploads/" . $model->getFilePath() . "/original/$time.jpg",
                                     'path_1024'     => "/uploads/" . $model->getFilePath() . "/1024/$time.jpg",
                                     'path_512'      => "/uploads/" . $model->getFilePath() . "/512/$time.jpg"]);

            User::inRandomOrder()->first()->books()->sync($model);
        });
    }

    public function definition(): array
    {
        return [
            'name'      => $this->faker->name,
            'is_active' => $this->faker->boolean,
            'position'  => $this->faker->numberBetween(0, 200),
            "author_id" => User::inRandomOrder()->value('id'),
        ];
    }
}
