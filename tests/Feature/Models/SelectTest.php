<?php

namespace Tests\Feature\Models;

use App\Models\Select;
use Tests\TestCase;

class SelectTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {


        $results = ['YES','NO'];
        \DB::connection()->enableQueryLog(); // 开启查询日志

        $s = Select::whereIn('key',$results)->get();
        $queries = \DB::getQueryLog(); // 获取查询日志



        dump($s->toArray());
        $this->assertTrue(true);
    }
}
