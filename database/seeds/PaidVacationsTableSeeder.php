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
				'user_id' => 1,
				'remaining_days' => 10.00,
				'original_paid_vacation' => 10,
				'start_date' => '2016-02-03',
				'limit_date' => '2018-02-02',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'user_id' => 1,
				'remaining_days' => 11.00,
				'original_paid_vacation' => 11,
				'start_date' => '2017-02-03',
				'limit_date' => '2019-02-02',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'user_id' => 2,
				'remaining_days' => 10.00,
				'original_paid_vacation' => 10,
				'start_date' => '2016-03-01',
				'limit_date' => '2018-02-28',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'user_id' => 2,
				'remaining_days' => 11.00,
				'original_paid_vacation' => 11,
				'start_date' => '2017-03-01',
				'limit_date' => '2019-02-28',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'user_id' => 3,
				'remaining_days' => 10.00,
				'original_paid_vacation' => 10,
				'start_date' => '2016-01-15',
				'limit_date' => '2018-01-14',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'user_id' => 3,
				'remaining_days' => 11.00,
				'original_paid_vacation' => 11,
				'start_date' => '2017-01-15',
				'limit_date' => '2019-01-14',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'user_id' => 4,
				'remaining_days' => 10.00,
				'original_paid_vacation' => 10,
				'start_date' => '2011-01-01',
				'limit_date' => '2012-12-31',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'user_id' => 4,
				'remaining_days' => 11.00,
				'original_paid_vacation' => 11,
				'start_date' => '2012-01-01',
				'limit_date' => '2013-12-31',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'user_id' => 4,
				'remaining_days' => 12.00,
				'original_paid_vacation' => 12,
				'start_date' => '2013-01-01',
				'limit_date' => '2014-12-31',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'user_id' => 4,
				'remaining_days' => 14.00,
				'original_paid_vacation' => 14,
				'start_date' => '2014-01-01',
				'limit_date' => '2015-12-31',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'user_id' => 4,
				'remaining_days' => 16.00,
				'original_paid_vacation' => 16,
				'start_date' => '2015-01-01',
				'limit_date' => '2016-12-31',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'user_id' => 4,
				'remaining_days' => 18.00,
				'original_paid_vacation' => 18,
				'start_date' => '2016-01-01',
				'limit_date' => '2017-12-31',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'user_id' => 4,
				'remaining_days' => 20.00,
				'original_paid_vacation' => 20,
				'start_date' => '2017-01-01',
				'limit_date' => '2018-12-31',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'user_id' => 5,
				'remaining_days' => 10.00,
				'original_paid_vacation' => 10,
				'start_date' => '2014-01-01',
				'limit_date' => '2015-12-31',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'user_id' => 5,
				'remaining_days' => 11.00,
				'original_paid_vacation' => 11,
				'start_date' => '2015-01-01',
				'limit_date' => '2016-12-31',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'user_id' => 5,
				'remaining_days' => 12.00,
				'original_paid_vacation' => 12,
				'start_date' => '2016-01-01',
				'limit_date' => '2017-12-31',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'user_id' => 5,
				'remaining_days' => 14.00,
				'original_paid_vacation' => 14,
				'start_date' => '2017-01-01',
				'limit_date' => '2018-12-31',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'user_id' => 5,
				'remaining_days' => 16.00,
				'original_paid_vacation' => 16,
				'start_date' => '2018-01-01',
				'limit_date' => '2019-12-31',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'user_id' => 6,
				'remaining_days' => 10.00,
				'original_paid_vacation' => 10,
				'start_date' => '2017-01-15',
				'limit_date' => '2019-01-14',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'user_id' => 7,
				'remaining_days' => 10.00,
				'original_paid_vacation' => 10,
				'start_date' => '2017-02-22',
				'limit_date' => '2019-02-21',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
		]);
	}

}
