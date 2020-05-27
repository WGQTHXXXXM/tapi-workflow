<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTplTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tpl_tasks', function (Blueprint $table) {
            $table->uuid('id')->comment('唯一id')->primary();
            $table->string("name")->comment('任务名');
            $table->uuid("tpl_id")->index()->comment("模板id");
            $table->uuid("ui_id")->comment("界面id");
            $table->float("position_x",8,2)->comment("坐标x");
            $table->float("position_y",8,2)->comment("坐标y");
            $table->float("width",8,2)->comment("宽");
            $table->float("height",8,2)->comment("高");
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
        Schema::dropIfExists('tpl_tasks');
    }
}
