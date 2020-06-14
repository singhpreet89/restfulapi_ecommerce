<?php

use App\User;
use App\Product;
use App\Category;
use App\Transaction;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        echo "\n\e[0;32m**********************************************\e[0m";
        echo "\n             \e[1;33mSEEDING IN PROGRESS\e[0m\n";
        echo "\e[0;31mNOTE: Seeding can take between 1 to 3 minutes.\e[0m";
        echo "\n\e[0;32m**********************************************\e[0m\n\n";

        $users = 1000;
        $numberOfCategories = 30;
        $numberOfProducts = 1000;
        $numberOfTransactions = 1000;

        factory(User::class, $users)->create();
        factory(Category::class, $numberOfCategories)->create();

        /** 
         * ! UPDATE THE PIVOT TABLE ALONG WITH PRODUCT CREATION
         * ! WITH THE CREATION OF EACH PRODUCT, UPDATE THE PIVOT TABLE WITH the product_id AND RANDOMLY SELECTED (1 TO 5) CATEGORIES
         * Every product that will be created needs some Categories.
         * So, Each product will be randomly assigned between 1 to 5 categories
         **/
        factory(Product::class, $numberOfProducts)->create()->each(function($product) {
            /**
             * THE GOAL IS TO ASSIGN A RANDOM NUMBER OF CATEGORIES TO A PRODUCT WHICH ARE RANDOMLY SELECTED
             * Category::all() returns a collection of all the Categories 
             * mt_rand(1, 5) generates a random number between 1 to 5 
             * random(number_of_rows) generates 'number_of_rows' randomly from the 'categories' table // ? i.e. between 1 to 5
             * Pluck their id's
             **/ 
            $categories = Category::all()->random(mt_rand(1, 5))->pluck('id');

            // ! UPDATING THE PIVOT TABLE
            $product->categories()->attach($categories);
        });

        factory(Transaction::class, $numberOfTransactions)->create();
    }
}
