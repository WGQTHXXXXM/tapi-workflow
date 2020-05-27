<?php

use App\Models\TplDecision;
use Illuminate\Database\Seeder;

class TplDecisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $data =  factory(TplDecision::class)->times(10)->create();

    }
}
