<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Article;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {
    return [
       'title'=>$faker->company(),
      'description'=>$faker->sentence(4),
      'type_id'=>rand(1,10)
    ];
});
