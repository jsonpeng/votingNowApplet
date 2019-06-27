<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateAwards1Table extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('awards', function (Blueprint $table) {
           
            $table->integer('sort')->nullable()->default(0)->comment('排序越大显示排名越靠前');
            $table->string('status')->nullable()->default('关闭')->comment('开启|关闭');

            $table->index('sort');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       
    }
}
