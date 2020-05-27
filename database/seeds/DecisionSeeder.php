<?php

use App\Models\Decision;
use Illuminate\Database\Seeder;

class DecisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $data =  factory(Decision::class)->times(10)->create();



    }
}
