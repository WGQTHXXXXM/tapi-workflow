<?php

namespace Tests\Feature;

use App\Models\Decision;
use App\Models\Select;
use App\Models\Task;
use App\Models\Template;
use Tests\TestCase;

class AllYesAlgorithmsTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {

        $tpl = Template::first();
        $ds = Decision::first();

        $tasks = Task::all();

        $namespace = 'App\\Algorithms\\';

        $className = 'AllYesAlgorithm';

        $nc = $namespace.$className;

        $al = new $nc($tpl,$ds);

        $al->compute(...$tasks);


    }
}
