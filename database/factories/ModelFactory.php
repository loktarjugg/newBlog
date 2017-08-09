<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {

    static $password , $email , $name , $is_admin;

    return [
        'name' => $name?: $faker->name,
        'email' => $email ?:$faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'is_admin' => $is_admin ?:false,
        'remember_token' => str_random(10),
    ];
});


$factory->define(\App\Models\Article::class, function (Faker\Generator $faker) {

    $user = \App\Models\User::first();

    $type = rand(0,1);

    return [
        'title' => $faker->sentence(6),
        'user_id' => $user ?$user->id :1,
        'slug' => $faker->slug(3),
        'cover_link' => $type === 1 ? $faker->imageUrl(240,160) : $faker->imageUrl(310,227),
        'desc' => $faker->text(150),
        'body_original' => $faker->realText(500),
        'body' => $faker->paragraph(3),
        'source_link' => $faker->url,
        'vote_count' => $faker->numberBetween(0,200),
        'view_count' => $faker->numberBetween(0,200),
        'replies_count' => $faker->numberBetween(0,200),
        'type' => $type === 1 ? 'articles' : 'works',
    ];

});

$factory->define(App\Models\Share::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->text(20),
        'desc' => $faker->text(120),
        'logo' => $faker->imageUrl(62,62),
        'link' => $faker->url,
    ];
});
