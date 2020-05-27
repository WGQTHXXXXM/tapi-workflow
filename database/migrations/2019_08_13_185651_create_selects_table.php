<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSelectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selects', function (Blueprint $table) {
            $table->uuid('id')->comment('唯一id')->primary();

            $table->string("name")->comment('名字');
            $table->string("key")->comment('唯一关键key用于标识');
            $table->string("type")->comment('用于标记按钮类别：feedback反馈，create创建，decision决策');
            $table->string("color", 10)->comment('颜色16进制');
            $table->integer("sort")->comment('排序越小越靠前，用于列表渲染');


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
        Schema::dropIfExists('selects');
    }
}
