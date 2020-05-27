<?php

use App\Models\TplTask;
use Illuminate\Database\Seeder;

class TplTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

       $data =  factory(TplTask::class)->times(10)->create();

    }
}
