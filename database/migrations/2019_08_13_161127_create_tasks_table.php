<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {


            $table->uuid('id')->comment('唯一id')->primary();

            $table->string("name")->comment('任务名');

            $table->uuid("instance_id")->comment('实例id')->index();
            $table->uuid("tpl_task_id")->comment('模板任务id');
            $table->string("status")->comment('状态：准备中(ready), 开始(start)，结束(end) ');
            $table->text("attributes")->comment('属性集')->nullable();
            $table->uuid("result")->comment('选择结果')->nullable();

            $table->bigInteger("start_time")->comment('开始时间')->nullable();
            $table->bigInteger("end_time")->comment('结束时间')->nullable();


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
        Schema::dropIfExists('tasks');
    }
}
