<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTplLinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tpl_lines', function (Blueprint $table) {
            $table->uuid('id')->comment('唯一id')->primary();

            $table->string("name")->comment('线程名')->nullable();
            $table->uuid("tpl_id")->comment('模板id')->index();
            $table->uuid("last_id")->comment('上一个节点id');
            $table->string("last_type")->comment('上一个节点类型');
            $table->integer("last_anchor")->comment('上一个节点锚点位');


            $table->uuid("next_id")->comment('下一个节点id');
            $table->string("next_type")->comment('下一个节点类型');
            $table->integer("next_anchor")->comment('下一个节点锚点位');



            $table->text("remark")->nullable();

            $table->bigInteger("created_at")->nullable();
            $table->bigInteger("updated_at")->nullable();
            $table->string("created_by")->nullable();
            $table->string("updated_by")->nullable();

            $table->index(['tpl_id','last_id','next_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tpl_lines');
    }
}
