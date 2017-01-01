<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('role')->default(2);
			$table->integer('department')->default(0);
			$table->integer('type_of_employment');
			$table->integer('status');
			$table->string('last_name');
			$table->string('first_name');
			$table->string('email')->unique();
			$table->string('password', 60);
			$table->date('date_of_entering');
			$table->date('base_date');
			$table->text('memo')->nullable()->default(NULL);
			$table->rememberToken();
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
		Schema::drop('users');
	}

}
