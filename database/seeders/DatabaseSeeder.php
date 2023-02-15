<?php

namespace Database\Seeders;

use App\Models\{Category, Product, User};
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(AdminSeeder::class);

        /**  @source https://laravel.com/docs/eloquent-factories#factory-states */
        User::factory(2)->activated()->create();
        User::factory(2)->inactive()->create();
        User::factory(2)->state([
                                    'first_name' => 'Abigail',
                                    'last_name'  => 'Otwell',
                                ])->make();
//createQuietly Create a collection of models and persist them to the database without dispatching any model "events"
        User::factory(2)->activated()->createQuietly();

        /**
         * sequence give data for faker. If count is greater than the number of the array, laravel starts looping the array again
         * @source https://laravel.com/docs/eloquent-factories#factory-states
         */
        User::factory()
            ->activated()
            ->sequence([
                           'first_name' => 'Jahongir',//1 , 3
                           'last_name'  => 'Tursunboyev',
                       ],
                       [
                           'first_name' => 'Alisher ',// 2
                           'last_name'  => 'Yangiboyev',
                       ])
            ->count(3)
            ->create();
        /**
         * create 5 ... 10 products for each created category
         * @source https://laravel.com/docs/eloquent-factories#has-many-relationships
         */
        Category::factory(2)->has(Product::factory()->count(random_int(5, 10)))->create();

        /**
         * create 5 ... 10 products for each created categoryy
         * @source https://laravel.com/docs/eloquent-factories#has-many-relationships-using-magic-methods
         */
        Category::factory(2)->hasProducts(random_int(5, 10))->create();

        /**
         * forAuthor (for{Relation}) automatically creates an author user::factory()
         * will create one user and connect it to all created categories
         * @source https://laravel.com/docs/eloquent-factories#belongs-to-relationships-using-magic-methods
         */
        Category::factory(2)
            ->forAuthor(['first_name' => 'Jahongir'])
            ->hasProducts(random_int(5, 10), ['is_active' => true])
            ->create();

        /**  @source https://laravel.com/docs/eloquent-factories#belongs-to-relationships */
        $user = User::factory()->activated();
        Category::factory()
            ->for($user, 'author')
            ->hasProducts(random_int(5, 10), ['is_active' => true]);
        Category::factory()
            ->for($user, 'author')
            ->hasProducts(random_int(5, 10), ['is_active' => true]);
        //$this->call(UserSeeder::class);
    }
}
