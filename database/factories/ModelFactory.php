<?php

use Faker\Generator as Faker;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});


$factory->define(App\Category::class, function (Faker $faker) {

    $name = $faker->word;
    (strlen($name) > 4) ?: $name = $name . $name;
    return [
        'name' => $name,
        'icon' => 'far fa-user',
    ];
});


$factory->define(App\Role::class, function (Faker $faker) {

    $name = $faker->word;
    (strlen($name) > 4) ?: $name = $name . $name;
    return [
        'name' => $name,
        'display_name' => $name,
    ];
});

$factory->define(App\Post::class, function (Faker $faker) {

    return [
        'title' => preg_replace('/[^A-Za-z0-9\-]/', '', $faker->sentence(3)),
        'body' => $faker->paragraphs(8, true),
        'slug' => $faker->slug,
        'category_id' => '1',
        'status' => '3',
        'published_at' => $faker->dateTimeBetween('-2 years', 'now'),
        'user_id' => Auth::user() ? Auth::user()->id: '1',
    ];
});

$factory->define(App\Comment::class, function (Faker $faker) {

    return [
        'post_id' => '1',
        'user_id' => '1',
        'body' => $faker->sentence(),
    ];
});