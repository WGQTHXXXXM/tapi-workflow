<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
    }

    public function testTime(){


        $d = new \DateTime();

        $d->setTimestamp(1565341989.650386);

        $d = \DateTime::createFromFormat('U.u',1565341989.650386);

        dump("hello",$d->format(DATE_RFC3339));
    }

    public function testFackerTime(){

        $faker = Faker\Factory::create();

        $t = $faker->unixTime();

        dump($t);
    }
}
