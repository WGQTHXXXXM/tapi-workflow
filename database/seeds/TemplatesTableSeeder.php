<?php

use Illuminate\Database\Seeder;
use App\Models\Template;

class TemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        factory(Template::class)->times(10)->create();
    }
}
