<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Seller;
use App\Transaction;
use Faker\Generator as Faker;

$factory->define(Transaction::class, function (Faker $faker) {
    // Select one random Seller from the list of Users who has one or More products to Sell
    $seller = Seller::has('products')->get()->random();

    /** 
     * Seller of the product can not be the Buyer of the Same Product 
     * So, select any User from the 'Users' table randomly, who is not the Seller of this Product.
     * 
     **/
    $buyer = User::all()->except($seller->id)->random();    

    return [
        'quantity' => $faker->numberBetween(1, 3),
        'buyer_id' => $buyer->id,
        'product_id' => $seller->products->random()->id,  // Select Random product of the Randomly selected Seller
    ];
});
