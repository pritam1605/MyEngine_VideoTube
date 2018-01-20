<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('access_url')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('country_code', 10)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('region_code', 10)->nullable();
            $table->string('region_name', 100)->nullable();
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
        Schema::dropIfExists('request_log');
    }
}
