<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderTicket extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('order', function (Blueprint $table) {
		    $table->increments('id');
		    $table->integer('status')->default('0');
		    $table->string('name',255)->nullable();
		    $table->string('phone',255)->nullable();
		    $table->string('email', 255)->nullable();
		    $table->string('address', 255)->nullable();
		    $table->text('comment')->nullable();
		    $table->integer('shipping')->default('0');
		    $table->integer('payment')->default('0');
		    $table->integer('payment_status')->default('0');
		    $table->integer('total')->default('0');
		    $table->timestamps();
		    $table->softDeletes();
	    });

	    Schema::create('ticket', function (Blueprint $table) {
		    $table->increments('id');
		    $table->integer('order_id')->default('0');
		    $table->integer('price')->default('0');
		    $table->integer('event_id')->default('0');
		    $table->integer('sector_id')->default('0');
		    $table->string('sector_name',255)->nullable();
		    $table->integer('row_id')->default('0');
		    $table->string('row_name',255)->nullable();
		    $table->integer('place_id')->default('0');
		    $table->string('place_name',255)->nullable();
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
	    Schema::dropIfExists('order');
	    Schema::dropIfExists('ticket');
    }
}
