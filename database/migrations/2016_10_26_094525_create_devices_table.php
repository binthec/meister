<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevicesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('devices', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('category')->nullable();
			$table->integer('os')->nullable();
			$table->string('name');
			$table->string('serial_id')->nullable();
			$table->date('bought_at');
			$table->integer('user_id')->nullable();
			$table->integer('status')->nullable();
			$table->integer('core')->nullable();
			$table->integer('memory')->nullable();
			$table->float('size')->nullable();
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
		Schema::drop('devices');
	}

}
