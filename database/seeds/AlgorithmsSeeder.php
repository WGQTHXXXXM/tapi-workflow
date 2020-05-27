<?php

use App\Models\Algorithm;
use Illuminate\Database\Seeder;

class AlgorithmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Algorithm::create([
            'name' => '全通过检测',
            'class_name' => 'AllYesAlgorithm',
            'results' => ["YES","NO"],
            'remark' => '备注',
        ]);

        Algorithm::create([
            'name' => '测试算法无效',
            'class_name' => 'TestAlgorithm',
            'results' => ["YES"],
            'remark' => '备注，只有返回yes',
        ]);

    }
}
