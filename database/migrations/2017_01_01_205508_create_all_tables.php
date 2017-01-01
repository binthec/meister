<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllTables extends Migration
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

		Schema::create('paid_vacations', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->float('remaining_days')->nullable();
			$table->integer('original_paid_vacation')->nullable();
			$table->date('start_date')->nullable();
			$table->date('limit_date')->nullable();
			$table->timestamps();
		});

		Schema::create('used_days', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->date('from')->nullable();
			$table->tinyInteger('from_am')->nullable()->default(0);
			$table->tinyInteger('from_pm')->nullable()->default(0);
			$table->date('until')->nullable();
			$table->integer('until_am')->nullable()->default(0);
			$table->float('used_days');
			$table->text('memo')->nullable();
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
		Schema::dropIfExists('devices');
		Schema::dropIfExists('paid_vacations');
		Schema::dropIfExists('used_days');
	}

}
