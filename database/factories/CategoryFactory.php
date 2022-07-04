<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Rcdelfin\Inventory\Models\Category;
use Faker\Generator as Faker;

$factory->define(Category::class, function (Faker $faker) {
	return [
		'name' => $faker->words(rand(1,4), true),
		'description' => $faker->sentence,
		'parent_id' => null
	];
});