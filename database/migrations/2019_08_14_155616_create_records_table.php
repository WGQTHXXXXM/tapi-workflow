<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->uuid('id')->comment('唯一id')->primary();

            $table->uuid('instance_id')->comment('实例id')->index();
            $table->uuid('task_id')->comment('任务id')->nullable()->index();

            $table->uuid("participant_id")->nullable()->comment('参与者id');

            $table->uuid("user_id")->comment('执行者id');
            $table->string("user_name")->comment('执行者name');

            $table->text("content")->nullable()->comment('日志内容');
            $table->string("type", 50)->comment('类型:decision决策，feedback反馈，create创建配置');
            $table->string("select_key", 50)->comment('select_key');
            $table->boolean("status")->comment('状态：有效，无效');

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
        Schema::dropIfExists('records');
    }
}
