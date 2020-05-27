<?php

namespace Tests\Feature\Models;

use App\Models\TplTask;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TplTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {

        \DB::connection()->enableQueryLog(); // 开启查询日志

        $t =  TplTask::with("selects")->find('1d69b260bdc111e9b0a5cbf25d3f37dc');

        $queries = \DB::getQueryLog(); // 获取查询日志



        dump($t->toArray());
        $this->assertTrue(true);
    }
}
