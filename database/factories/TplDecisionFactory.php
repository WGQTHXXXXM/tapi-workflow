<?php

use App\Models\Template as Template;
use App\Models\TplDecision;
use App\Models\TplUi as TplUi;
use Faker\Generator as Faker;

$factory->define(TplDecision::class, function (Faker $faker) {


    $tpiId = Template::value('id');
    $uiId = TplUi::value('id');

    return [
        'name' => $faker->name,
        'tpl_id' => $tpiId,
        'algorithm_id' => $uiId,
        'position_x' => $faker->randomFloat(2, 100, 500),
        'position_y' => $faker->randomFloat(2, 100, 500),
        'remark' => $faker->text,
        //
    ];
});
