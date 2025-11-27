<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPricePlace extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('event_price', function (Blueprint $table) {
		    $table->increments('id');
		    $table->integer('event_id')->default('0');
		    $table->integer('place_id')->default('0');
		    $table->integer('price')->default('0');
		    $table->integer('limit')->default('0');
		    $table->string('source', 255);
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
	    Schema::dropIfExists('event_price');
    }
}
