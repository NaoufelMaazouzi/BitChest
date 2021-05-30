<?php

use Faker\Generator as Faker;

$factory->define(App\Product::class, function (Faker $faker) {
    // Ici on crée des fausses données d'exemple en base de données grâce au module Faker
    return [
        'name' => $faker->sentence($nbWords = 3, $variableNbWords = false),
        'description' => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'price' => $faker->randomFloat($nbMaxDecimals = 2, $min = 0, $max = 99),
        'status' => $faker->randomElements($array = array ('published','unpublished'), $count = 1)[0],
        'state' => $faker->randomElements($array = array ('standard','solde'), $count = 1)[0],
        'reference' => $faker->bothify('?###??##?#??#?##'),
    ];
});
