<?php

use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    return [
        'name' => $faker->text($maxNbChars = 100),
        'description' => $faker->paragraph(),
        'price' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 99),
        'size' => $faker->randomElements($array = array ('XS','S','M','L','XL'), $count = 1)[0],
        'status' => $faker->randomElements($array = array ('published','unpublished'), $count = 1)[0],
        'state' => $faker->randomElements($array = array ('standard','solde'), $count = 1)[0],
        'reference' => $faker->text($maxNbChars = 16),
    ];
});
