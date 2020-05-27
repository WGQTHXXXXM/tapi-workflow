<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(TemplatesTableSeeder::class);
        $this->call(TplUiSeeder::class);
        $this->call(TplTaskSeeder::class);
        $this->call(TplDecisionSeeder::class);
        $this->call(InstanceSeeder::class);
        $this->call(DecisionSeeder::class);


    }
}
