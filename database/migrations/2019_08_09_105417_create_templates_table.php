<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->uuid('id')->comment('唯一id')->primary();
            $table->string("name")->unique();

            $table->uuid("ui_id")->comment('创建实例的 ui_id')->nullable();

            $table->text("remark")->nullable();
            $table->boolean("lock")->nullable();            //锁定后将无法修改

            $table->integer("init_x")->nullable()->default(50);
            $table->integer("init_y")->nullable()->default(200);

            $table->integer("end_x")->nullable()->default(250);
            $table->integer("end_y")->nullable()->default(200);

            $table->uuid("end_ui_id")->comment('结束ui_id')->nullable();


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
        Schema::dropIfExists('templates');
    }
}
