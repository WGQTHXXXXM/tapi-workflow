<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTplDecisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tpl_decisions', function (Blueprint $table) {
            $table->uuid('id')->comment('唯一id')->primary();
            $table->string("name")->comment('决策名');
            $table->uuid("tpl_id")->comment('模板id')->index();
            $table->uuid("algorithm_id")->comment('计算因子id');


            $table->text("select_result")->comment('选项结果关系 :[{"select_id":"选项id","tpl_line_id":"流程id"}]')->nullable();

            $table->float("position_x",8,2)->comment("坐标x");
            $table->float("position_y",8,2)->comment("坐标y");


            $table->text("remark")->nullable();

            $table->bigInteger("created_at")->nullable();
            $table->bigInteger("updated_at")->nullable();
            $table->string("created_by")->nullable();
            $table->string("updated_by")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tpl_decisions');
    }
}
