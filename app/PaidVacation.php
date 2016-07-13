<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PaidVacation extends Model {

	protected $table = 'paid_vacations';
	public $today;

	public function __construct() {
		$this->today = Carbon::now()->toDateString(); //今日の日付を取得;
	}

	public function user() {
		return $this->belongsTo('App¥User');
	}

	/**
	 * 本日時点での、付与されている有給日数の計算
	 * @param type $base_date
	 * @param type $start_date
	 * @return int
	 */
	public function getPaidVacation($base_date = null, $start_date = null) {
		$base_date = Carbon::createFromFormat('Y-m-d', $base_date);
		$start_date = Carbon::createFromFormat('Y-m-d', $start_date);

		//$years = $start_date->diffInYears($base_date);

		if ($this->today == $start_date || $start_date->isPast()) { //今日が起算日か、起算日を過ぎていれば
			//起算日から何年経っているかの年数(入社日からではない)
			$passed_years = $start_date->diffInYears($base_date);
			//$passed_years = Carbon::createFromDate('2015', '3', '25')->age; //テスト用日数
			//dd($passed_years);
			switch ($passed_years) {
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
	 * 入社してから今日日付までに付与されている有給を計算してレコードを保存するメソッド
	 * 引数：入社日、起算日、ユーザID
	 */
	public function calcRemainingDays($date_of_entering = null, $base_date = null, $user_id = null) {
		$start_date = $base_date; //起算日を最初の有給の有効期限開始日に格納

		while ($this->today > $start_date) {
			$paid_vacations = new PaidVacation; //新規にインスタンス生成
			$paid_vacations->user_id = $user_id;

			$paid_vacations->start_date = $start_date;
			$paid_vacations->limit_date = Carbon::createFromFormat('Y-m-d', $start_date)->addYear(2)->subDay(); //起算日から2年後マイナス1日が期限日
			$paid_vacations->remaining_days = $paid_vacations->getPaidVacation($base_date, $start_date); //有給日数を取得：有給取得によって減算されるカラム
			$paid_vacations->original_paid_vacation = $paid_vacations->getPaidVacation($base_date, $start_date); //有給日数を取得：本来の日数を記憶しておくためのカラム。減算されない。
			$paid_vacations->save();

			$start_date = Carbon::createFromFormat('Y-m-d', $start_date)->addYear(1)->toDateString(); //有給の有効期限開始日
		}
	}

	public function resetRemainingDays() {
		//
	}

}
