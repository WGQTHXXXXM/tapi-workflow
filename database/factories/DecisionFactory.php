<?php

use App\Models\Decision as Decision;
use App\Models\Instance as Instance;
use App\Models\Template as Template;
use App\Models\TplUi as TplUi;
use Faker\Generator as Faker;

$factory->define(Decision::class, function (Faker $faker) {


    $instanceId = Instance::value('id');

    $tplDecisionId = \App\Models\TplDecision::value('id');

    return [
        'name' => $faker->name,
        'instance_id' => $instanceId,
        'tpl_decision_id' => $tplDecisionId,


        'remark' => $faker->text,
        //
    ];
});
