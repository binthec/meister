<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsedDaysTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('used_days', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id');
			$table->date('from')->nullable();
			$table->integer('from_am')->nullable()->default(0);
			$table->integer('from_pm')->nullable()->default(0);
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
		Schema::drop('used_days');
	}

}
