<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('active')->default(1);
            $table->integer('ganre_id');
            $table->string('title');
            $table->text('image')->nullable();
            $table->string('logo')->nullable();
            $table->text('body')->nullable();
            $table->string('site_url')->nullable();
            $table->text('rules')->nullable();
            $table->integer('video_count')->default(0)->unsigned();
            $table->boolean('online')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
