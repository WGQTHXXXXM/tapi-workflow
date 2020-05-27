<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->uuid('id')->comment('唯一id')->primary();


            $table->uuid('task_id')->comment('任务id')->index();
            $table->uuid('instance_id')->comment('实例id')->index();

            $table->string("name", 50)->comment('参与者名称');

            $table->uuid("key_id")->comment('用户组织唯一id');
            $table->string("type", 50)->comment('类型：  个人(individual) or 组织(group)');
            $table->string("code", 50)->comment('用作特殊字段，前端自行处理,当成特殊用户')->nullable();


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
        Schema::dropIfExists('participants');
    }
}
