<?php

use Illuminate\Database\Seeder;

class PaidVacationsTableSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('paid_vacations')->truncate();
		DB::table('paid_vacations')->insert([
			[
				'user_id' => 3,
				'remaining_days' => 10.00,
				'original_paid_vacation' => 10,
				'start_date' => '2016-02-03',
				'limit_date' => '2018-02-02',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'user_id' => 3,
				'remaining_days' => 11.00,
				'original_paid_vacation' => 11,
				'start_date' => '2017-02-03',
				'limit_date' => '2019-02-02',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
		]);
	}

}
