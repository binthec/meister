<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('users')->truncate();
		DB::table('users')->insert([
			[
				'role' => 1,
				'status' => 1,
				'type_of_employment' => 1,
				'department' => 1,
				'last_name' => '大分',
				'first_name' => 'かぼす',
				'email' => 'o-kabosu@gmail.com',
				'password' => bcrypt('aaaaaaaa'),
				'date_of_entering' => '2015-08-03',
				'base_date' => '2016-02-03',
				'memo' => '備考備考備考備考',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'role' => 20,
				'status' => 1,
				'type_of_employment' => 1,
				'department' => 1,
				'last_name' => '鈴木',
				'first_name' => '太郎',
				'email' => 's-taro@gmail.com',
				'password' => bcrypt('aaaaaaaa'),
				'date_of_entering' => '2015-09-01',
				'base_date' => '2016-03-01',
				'memo' => '備考備考備考備考',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'role' => 20,
				'status' => 1,
				'type_of_employment' => 1,
				'department' => 50,
				'last_name' => '高橋',
				'first_name' => '翔',
				'email' => 't-sho@gmail.com',
				'password' => bcrypt('aaaaaaaa'),
				'date_of_entering' => '2015-07-15',
				'base_date' => '2015-01-15',
				'memo' => '備考備考備考備考',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'role' => 20,
				'status' => 1,
				'type_of_employment' => 50,
				'department' => 1,
				'last_name' => '田中',
				'first_name' => 'さくら',
				'email' => 't-sakura@gmail.com',
				'password' => bcrypt('aaaaaaaa'),
				'date_of_entering' => '2010-07-01',
				'base_date' => '2011-01-01',
				'memo' => '備考備考備考備考',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'role' => 20,
				'status' => 99,
				'type_of_employment' => 1,
				'department' => 1,
				'last_name' => '木村',
				'first_name' => '孝之',
				'email' => 'k-takayuki@gmail.com',
				'password' => bcrypt('aaaaaaaa'),
				'date_of_entering' => '2013-07-01',
				'base_date' => '2014-01-01',
				'memo' => '備考備考備考備考',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'role' => 1,
				'status' => 1,
				'type_of_employment' => 1,
				'department' => 50,
				'last_name' => '吉田',
				'first_name' => 'さくら',
				'email' => 'y-sakura@gmail.com',
				'password' => bcrypt('aaaaaaaa'),
				'date_of_entering' => '2016-07-15',
				'base_date' => '2017-01-15',
				'memo' => '備考備考備考備考',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
			[
				'role' => 20,
				'status' => 1,
				'type_of_employment' => 1,
				'department' => 0,
				'last_name' => '高橋',
				'first_name' => '梅',
				'email' => 't-ume@gmail.com',
				'password' => bcrypt('aaaaaaaa'),
				'date_of_entering' => '2016-08-22',
				'base_date' => '2017-02-22',
				'memo' => '備考備考備考備考',
				'created_at' => Carbon\Carbon::now(),
				'updated_at' => Carbon\Carbon::now(),
			],
		]);
	}

}
