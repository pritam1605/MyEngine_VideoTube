<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('channel_id')->unsigned()->nullable();
            $table->string('uid')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('processed')->default(false);
            $table->string('online_video_id', 100)->nullable();
            $table->string('online_video_filename')->nullable();
            $table->enum('visibility', ['public', 'unlisted', 'private']);
            $table->boolean('allows_vote')->default(true);
            $table->boolean('allows_comment')->default(true);
            $table->integer('processed_percentage')->default(0);
            $table->softDeletes();

            $table->foreign('channel_id')
                  ->references('id')
                  ->on('channel')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('video');
    }
}
