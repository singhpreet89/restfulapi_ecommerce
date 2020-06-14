<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
    return [
        // 'name' => $faker->word,
        'name' => $faker->unique()->sentence($nbWords = 6, $variableNbWords = true),
        'description' => $faker->paragraph(1),
    ];
});
