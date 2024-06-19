<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    
    return [
        'customProfile' => 'simpleAccount',
            'username' => fake()->lastName(),
            'name' => fake()->name(),
            'generatedCustomId' => fake()->numberBetween(1,900),
            'country' => fake()->country(),
            'city' => fake()->city(),
            'telephone1' => fake()->numberBetween(600000000,700000000),
            'telephone2' => fake()->numberBetween(600000000,700000000),
            'email' => fake()->unique()->safeEmail(),
            'active'=> 1,
            'type'=> 'business',
            'deleted' => 0,
            'region'=> fake()->streetName(),
            'register' => 10,
            'taxPayerNumber' =>fake()->numberBetween(1000,9000)
    ];
});
