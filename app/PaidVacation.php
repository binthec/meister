<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PaidVacation extends Model
{

	protected $table = 'paid_vacations';
	protected static $today;

	public function __construct()
	{
		self::$today = Carbon::now()->toDateString(); //今日の日付を取得;
	}

	public function user()
	{
		return $this->belongsTo('App¥User');
	}

	/**
	 * 本日時点での、付与されている有給日数の計算
	 * @param type $baseDate 起算日
	 * @param type $startDate
	 * @return int
	 */
	public static function getOriginalPaidVacation($baseDate, $startDate)
	{
		$baseDate = Carbon::createFromFormat('Y-m-d', $baseDate);
		$startDate = Carbon::createFromFormat('Y-m-d', $startDate);

		//$years = $start_date->diffInYears($base_date);

		if (self::$today == $startDate || $startDate->isPast()) { //今日が起算日か、起算日を過ぎていれば
			//起算日から何年経っているかの年数(入社日からではない)
			$passedYears = $startDate->diffInYears($baseDate);
			//$passedYears = Carbon::createFromDate('2015', '3', '25')->age; //テスト用日数
			//dd($passed_years);
			switch ($passedYears) {
				case 0: //起算日から1年未満（＝入社半年～1年半の間）の場合、10日付与
					return 10;
					break;
				case 1: //起算日から1年以上2年未満（＝入社1年半～2年半の間）の場合、11日付与
					return 11;
					break;
				case 2:
					return 12;
					break;
				case 3:
					return 14;
					break;
				case 4:
					return 16;
					break;
				case 5:
					return 18;
					break;
				default:
					return 20;
					break;
			}
		} else { //起算日を過ぎていなければまだ有給は無いので0を返す
			return 0;
		}
	}

	/**
	 * 入社してから今日日付までに付与されている有給を計算して、レコードに存在しない部分を追加するメソッド
	 * 何もレコードを持っていないユーザの場合は全て新規作成になる
	 * 
	 * @param int $userId
	 * 返り値なし
	 */
	public static function setOriginalPaidVacations($userId)
	{

		$paidVacation = PaidVacation::where('user_id', $userId)->orderBy('start_date', 'desc')->get();

		if ($paidVacation->count() > 0) {
			//既に登録されている有給レコードが存在する場合、最新のレコードの有給期限開始日を取得して代入
			$startDate = Carbon::createFromFormat('Y-m-d', $paidVacation->first()->start_date)->addYear(1)->toDateString();
		} else {
			//登録されている有給レコードが存在しないので、userテーブルにある起算日を代入
			$startDate = User::where('id', $userId)->first()->base_date;
		}

		$baseDate = User::where('id', $userId)->first()->base_date; //起算日
		while (self::$today > $startDate) {
			$paidVacation = new PaidVacation; //新規にインスタンス生成
			$paidVacation->user_id = $userId;

			$paidVacation->start_date = $startDate;
			$paidVacation->limit_date = Carbon::createFromFormat('Y-m-d', $startDate)->addYear(2)->subDay(); //起算日から2年後マイナス1日が期限日
			$paidVacation->remaining_days = self::getOriginalPaidVacation($baseDate, $startDate); //有給日数を取得：有給取得によって減算されるカラム
			$paidVacation->original_paid_vacation = self::getOriginalPaidVacation($baseDate, $startDate); //有給日数を取得：本来の日数を記憶しておくためのカラム。減算されない。
			$paidVacation->save();

			$startDate = Carbon::createFromFormat('Y-m-d', $startDate)->addYear(1)->toDateString(); //有給の有効期限開始日
		}
	}

	public static function recalcRemainingDays($userId)
	{
		$paidVacations = PaidVacation::where('user_id', $userId)->orderBy('start_date', 'asc')->get();
		$usedDays = UsedDays::where('user_id', $userId)->orderBy('from', 'asc')->get();

//		foreach ($paidVacations as $paidVacation) {
//			
//		}
	}

}
