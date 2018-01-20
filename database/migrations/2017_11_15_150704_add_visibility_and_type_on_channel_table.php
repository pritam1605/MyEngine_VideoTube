<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVisibilityAndTypeOnChannelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('channel', function (Blueprint $table) {
            $table->enum('type', ['free', 'paid'])->after('image_filename')->default('free');
            $table->enum('visibility', ['public', 'unlisted', 'private'])->after('type')->default('public');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('channel', function (Blueprint $table) {
            $table->dropColumn(['visibility', 'type']);
        });
    }
}
