<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('seller_id')->unsigned()->index()->nullable();
            $table->integer('buyer_id')->unsigned()->index()->nullable();
            $table->integer('item_id')->unsigned()->index()->nullable();
            $table->string('item_type');
            $table->decimal('sale_price', 8, 2);
            $table->decimal('sale_commission', 8, 2);

            $table->foreign('seller_id')
                  ->references('id')
                  ->on('user')
                  ->onDelete('set null');

            $table->foreign('buyer_id')
                  ->references('id')
                  ->on('user')
                  ->onDelete('set null');

            $table->foreign('item_id')
                  ->references('id')
                  ->on('channel')
                  ->onDelete('set null');

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
        Schema::dropIfExists('sale');
    }
}
