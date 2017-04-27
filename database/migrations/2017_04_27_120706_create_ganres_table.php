<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGanresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ganres', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active')->default(1);
            $table->string('title');
            $table->string('image')->nullable();
            $table->integer('video_count')->default(0)->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ganres');
    }
}
