<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instances', function (Blueprint $table) {
            $table->uuid('id')->comment('唯一id')->primary();
            $table->uuid('tpl_id')->comment('模板id');

            $table->string("name")->comment('任务名');
            $table->bigInteger("start_time")->comment('开始时间')->nullable();
            $table->bigInteger("end_time")->comment('结束时间')->nullable();
            $table->text("attributes")->comment('属性集')->nullable();


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
        Schema::dropIfExists('instances');
    }
}
