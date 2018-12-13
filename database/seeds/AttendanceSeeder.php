
<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $attendances = $this->createRandomDataset(7, '2018','2018');

        /* データ数が多いと止まる場合があるため、分割して挿入 */
        $chunk = array_chunk($attendances, 5000);
        DB::table('attendances')->truncate();
        foreach ($chunk as $value) {
            DB::table('attendances')->insert($value);
        }
    }

    /**
     * 年月を指定して各日の出退勤データを生成
     * @param  $amount = 一日毎に生成するデータ数
     *         $startDate_str, $endDate_str = 開始・終了の日付 (例: 2018, 2018-01, 2018-01-01　など)
     * @return array (attendaceモデル)
     */
    public function createRandomDataset( $user_num, $startDate_str, $endDate_str ){
        $dataset = [];
        $carbons = $this->getBtweenCarbonDate( $startDate_str, $endDate_str );

        if (array_key_exists('start', $carbons)  && array_key_exists('end', $carbons ))
        {
            $carbon = $carbons['start'];
            $totalDays = $carbon->diffInDays($carbons['end']);

            for ( $count = 0; $count <  $totalDays; $count++ ) {

                for( $m = 1 ; $m < $user_num ; $m++)
                {
                    $user_id = $m;

                    $start_date = Carbon::create( $carbon->year, $carbon->month, $carbon->day, rand(6, 12), rand(0, 59));
                    $end_date = $start_date->copy()->addHours( rand(6, 12) )->addMinutes( rand(0, 59) );

                    $attend_start = [
                        'user_id' => $user_id,
                        'status' => 10,
                        'slack_text' => 'dummy',
                        'raw_data' => 'テストデータ（出勤）',
                        'created_at' => $start_date,
                        'updated_at' => $start_date,
                    ];
                    $attend_end = [
                        'user_id' => $user_id,
                        'status' => 90,
                        'slack_text' => 'dummy',
                        'raw_data' => 'テストデータ（退勤）',
                        'created_at' => $end_date,
                        'updated_at' => $end_date,
                    ];
                    array_push($dataset, $attend_start, $attend_end );
                }
                $carbon->addDay();
            }
            return $dataset;
        }
        return false;
    }

    /**
     * 開始・終了の日付を文字列を、開始・終了日を考慮してCarbonオブジェクトに変換する
     *
     * @return Carbonオブジェクトを要素とする配列
     */
    function getBtweenCarbonDate($start_str=null, $end_str = null){

        $dates = [];

        /* 文字列から日付部分を取得 */
        preg_match_all('/\d{1,4}/', $start_str, $match_start);
        preg_match_all('/\d{1,4}/', $end_str, $match_end);

        if ( $start_str !== null)
        {
            $start_carbon = new Carbon( $start_str );
            /* 年のみの場合は、開始月を1月とする */
            if ( count( $match_start[0] ) === 1 )
            {
                $start_carbon->year = intval( $match_start[0][0] );
                $start_carbon->firstOfYear();
            }
            $dates['start'] = $start_carbon ;
        }
        if ( $end_str !== null )
        {
            $end_carbon = new Carbon( '2019' );

            /* 年のみの場合は、終了月を12月とする */
            if (count($match_end[0]) === 1)
            {
                $end_carbon->year = intval( $match_end[0][0] );
                $end_carbon->month = 12;
                $end_carbon->endOfMonth();
            }
            /* 年月の場合は、その月の末日とする (デフォルトが1日のため変更) */
            else if (count($match_end[0]) === 2)
            {
                $end_carbon->endOfMonth();
            }
            $dates['end'] = $end_carbon;
        }
        return $dates;
    }

}
