<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;

use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name'          => $faker->sentence,
        'free_shipping' => $faker->boolean,
        'description'   => $faker->text,
        'price'         => $faker->randomNumber(4),
    ];
});
