<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLocationObjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('location', function (Blueprint $table) {
		    $table->increments('id');
		    $table->string('name',255);
		    $table->string('address',255);
		    $table->string('image', 255)->nullable();
		    $table->integer('status');
		    $table->timestamps();
		    $table->softDeletes();
	    });

	    Schema::create('scheme', function (Blueprint $table) {
		    $table->increments('id');
		    $table->string('name',255);
		    $table->integer('status');
		    $table->timestamps();
		    $table->softDeletes();
	    });

	    Schema::create('scheme_sector', function (Blueprint $table) {
		    $table->increments('id');
		    $table->integer('scheme_id')->default('0');
		    $table->integer('type');
		    $table->integer('svg_place_radius')->default('0');
		    $table->integer('svg_place_radius_max')->default('0');
		    $table->text('svg_path');
		    $table->string('name',255);
		    $table->integer('status');
		    $table->timestamps();
		    $table->softDeletes();
	    });

	    Schema::create('scheme_place', function (Blueprint $table) {
		    $table->increments('id');
		    $table->integer('sector_id')->default('0');
		    $table->string('svg_row',255);
		    $table->string('svg_place',255);
		    $table->integer('svg_x');
		    $table->integer('svg_y');
		    $table->integer('status');
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
	    Schema::dropIfExists('scheme_place');
	    Schema::dropIfExists('scheme_sector');
	    Schema::dropIfExists('scheme');
	    Schema::dropIfExists('location');
    }
}
