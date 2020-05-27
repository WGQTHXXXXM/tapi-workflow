<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlgorithmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('algorithms', function (Blueprint $table) {

            $table->uuid('id')->comment('唯一id')->primary();

            $table->string("name")->comment('计算因子名');
            $table->string("class_name",200)->comment('类名：不含命名空间');


            $table->text("results")->comment("结果集：['选项KEY','选项KEY']");


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
        Schema::dropIfExists('algorithms');
    }
}
