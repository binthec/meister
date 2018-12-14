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
        if (count(DB::table('devices')->get()) === 0) {
            DB::table('users')->insert([
                [
                    'id' => 1,
                    'role' => 1,
                    'status' => 1,
                    'type_of_employment' => 50,
                    'department' => 1,
                    'last_name' => '江藤',
                    'first_name' => '勝彦',
                    'email' => 'katsu@moremost.jp',
                    'password' => bcrypt('aaaaaaaa'),
                    'date_of_entering' => '2013-04-01',
                    'base_date' => '2013-010-01',
                    'memo' => '',
                    'created_at' => Carbon\Carbon::now(),
                    'updated_at' => Carbon\Carbon::now(),
                ],
                [
                    'id' => 2,
                    'role' => 1,
                    'status' => 1,
                    'type_of_employment' => 50,
                    'department' => 1,
                    'last_name' => '佐藤',
                    'first_name' => '裕也',
                    'email' => 'sato@moremost.jp',
                    'password' => bcrypt('aaaaaaaa'),
                    'date_of_entering' => '2014-04-01',
                    'base_date' => '2014-010-01',
                    'memo' => '',
                    'created_at' => Carbon\Carbon::now(),
                    'updated_at' => Carbon\Carbon::now(),
                ],
                [
                    'id' => 3,
                    'role' => 1,
                    'status' => 1,
                    'type_of_employment' => 1,
                    'department' => 1,
                    'last_name' => '石橋',
                    'first_name' => '美内子',
                    'email' => 'ishi@moremost.jp',
                    'password' => bcrypt('aaaaaaaa'),
                    'date_of_entering' => '2015-08-03',
                    'base_date' => '2016-02-03',
                    'memo' => '',
                    'created_at' => Carbon\Carbon::now(),
                    'updated_at' => Carbon\Carbon::now(),
                ],
                [
                    'id' => 4,
                    'role' => 1,
                    'status' => 1,
                    'type_of_employment' => 1,
                    'department' => 50,
                    'last_name' => '生田',
                    'first_name' => '弥佳',
                    'email' => 'mika@moremost.jp',
                    'password' => bcrypt('aaaaaaaa'),
                    'date_of_entering' => '2016-08-01',
                    'base_date' => '2017-02-01',
                    'memo' => '',
                    'created_at' => Carbon\Carbon::now(),
                    'updated_at' => Carbon\Carbon::now(),
                ],
            ]);
        }
    }

}
