<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPriceColor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('event_price_color', function (Blueprint $table) {
		    $table->increments('id');
		    $table->integer('event_id')->default('0');
		    $table->integer('from')->default('0');
		    $table->integer('to')->default('0');
		    $table->string('color')->default('');
		    $table->integer('status')->default('0');
		    $table->timestamps();
		    $table->softDeletes();
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
	    Schema::dropIfExists('event_price_color');
    }
}
