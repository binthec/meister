<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaidVacationsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('paid_vacations', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->float('remaining_days')->nullable();
			$table->integer('original_paid_vacation')->nullable();
			$table->date('start_date')->nullable();
			$table->date('limit_date')->nullable();
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
		Schema::drop('paid_vacations');
	}

}
