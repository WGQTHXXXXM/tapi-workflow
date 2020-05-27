<?php

use App\Models\Instance;
use Illuminate\Database\Seeder;

class InstanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data =  factory(Instance::class)->times(10)->create();

//        dd($data->toArray());

    }
}
