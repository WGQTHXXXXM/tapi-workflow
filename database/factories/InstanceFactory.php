<?php

use App\Models\Template as Template;
use Faker\Generator as Faker;

$factory->define(App\Models\Instance::class, function (Faker $faker) {

    $tpiId = Template::value('id');

    return [
        //
        'name' => $faker->name,
        'tpl_id' => $tpiId,
        'start_time' => nowTimeMs(),
        'end_time' => nowTimeMs(),
        'attributes' => [
            "key1" => "b0853ac0bcde11e9b7b89961ed473c3e",
            "key2" => "Darlene Gusikowski",
            "key3" => "Ms. Daniela Bartoletti IV",
            "key4" => 1565600024179],
        'remark' => $faker->text,
    ];
});
