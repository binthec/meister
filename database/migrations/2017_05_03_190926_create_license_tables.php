<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLicenseTables extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('licenses', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->integer('maker_id')->unsigned();
			$table->string('product_key')->nullable();
			$table->integer('number')->unsigned()->default(1);
			$table->date('expired_on');
			$table->timestamps();
			$table->softDeletes();
		});
		Schema::create('makers', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->timestamps();
			$table->softDeletes();
		});
		Schema::create('license_user', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('lisence_id')->unsigned();
			$table->integer('user_id')->unsigned();
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
		Schema::dropIfExists('licenses');
		Schema::dropIfExists('makers');
		Schema::dropIfExists('license_user');
	}

}
