<?php

use App\Models\TplUi;
use Illuminate\Database\Seeder;

class TplUiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        TplUi::create([
            'name' => '界面1',
            'component' => 'component1',
            'remark' => 'demo测试'
        ]);

        TplUi::create([
            'name' => '界面2',
            'component' => 'component2',
            'remark' => 'demo测试'
        ]);

        TplUi::create([
            'name' => '界面2',
            'component' => 'component3',
            'remark' => 'demo测试'
        ]);
    }
}
