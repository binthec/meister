<?php

use Illuminate\Database\Seeder;

class DevicesTableSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('devices')->truncate();
		DB::table('devices')->insert([
			[
				'category' => 1,
				'os' => 1,
				'name' => '江藤さんMacBookAir',
				'serial_id' => '',
				'bought_at' => '2013-04-01',
				'user_id' => 1,
				'status' => 1,
				'core' => 4,
				'memory' => 8,
				'capacity' => 500,
				'size' => 13,
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'category' => 1,
				'os' => 1,
				'name' => '佐藤さんMacBookPro',
				'serial_id' => '',
				'bought_at' => '2016-07-01',
				'user_id' => 2,
				'status' => 1,
				'core' => 8,
				'memory' => 16,
				'capacity' => 1000,
				'size' => 15,
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'category' => 1,
				'os' => 1,
				'name' => 'ちっさいMacBookAir',
				'serial_id' => '123456789',
				'bought_at' => '2013-10-01',
				'user_id' => 3,
				'status' => 1,
				'core' => 4,
				'memory' => 8,
				'capacity' => 500,
				'size' => 11,
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'category' => 40,
				'os' => null,
				'name' => 'BenQの24インチディスプレイ',
				'serial_id' => '',
				'bought_at' => '2016-02-01',
				'user_id' => 3,
				'status' => 1,
				'core' => null,
				'memory' => null,
				'capacity' => null,
				'size' => 24,
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'category' => 2,
				'os' => 1,
				'name' => '総務用デスクトップMacPC',
				'serial_id' => '',
				'bought_at' => '2014-04-01',
				'user_id' => 4,
				'status' => 1,
				'core' => 4,
				'memory' => 8,
				'capacity' => 500,
				'size' => 24,
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
		]);
	}

}
