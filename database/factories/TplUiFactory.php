<?php

use App\Models\TplUi;
use Faker\Generator as Faker;

$factory->define(TplUi::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'component' =>$faker->name,
        'remark' => $faker->text,
        //
    ];
});
