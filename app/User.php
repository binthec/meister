<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\PaidVacation;
use App\UsedDays;
use Carbon\Carbon;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{

	use Authenticatable,
	 CanResetPassword;

	protected $table = 'users';
	protected $fillable = ['email', 'password'];
	protected $hidden = ['password', 'remember_token'];
	public $today;

	public function __construct()
	{
		$this->today = Carbon::now()->toDateString(); //今日の日付を取得;
	}

	public function paidVacation()
	{
		return $this->hasMany('App\PaidVacation'); //開始日の若い順に返す
	}

	public function usedDays()
	{
		return $this->hasMany('App\UsedDays');
	}

	/**
	 * 引数がtrueだったらカーボンのオブジェクトを返す。falseだったら日付('2016-04-07')を返す
	 * @param type $bool
	 * @return type
	 */
	public static function getTodayDate($bool = null)
	{
		if ($bool == true) {
			$today = Carbon::now();
		} else {
			$today = Carbon::now()->toDateString();
		}
		return $today;
	}

	/**
	 * 'Y年m月d日'の形式のデータを'Y-m-d'に変換するメソッド
	 * 
	 * @param type $date 
	 */
	public static function getStdDate($date)
	{
		return Carbon::createFromFormat('Y年m月d日', $date)->toDateString();
	}

	/**
	 * 'Y-m-d'の形式のデータを'Y年m月d日'に変換するメソッド
	 * 
	 * @param type $date
	 * @return Y-m-d
	 */
	public static function getJaDate($date)
	{
		return date('Y年m月d日', strtotime($date));
	}

//	public function getRemainingDays() { //有給残日数の計算メソッド
//		$paid_vacations = $this->PaidVacation;
//		$remaining_days = 0;
//		foreach ($paid_vacations as $paid_vacation) {
//			if ($paid_vacation->start_date < $this->today && $paid_vacation->limit_date > $this->today) {
//				$remaining_days += $paid_vacation->remaining_days;
//			}
//		}
//		return $remaining_days;
//	}

	/**
	 * 有効な(期限が切れてない)有給レコードのコレクションを返すメソッド
	 * sortが引数で渡って来なかった場合は早く期限が切れる順（＝古い順）にコレクションを返す
	 * @param string $sort
	 * @return @type Collection
	 */
	public function getValidPaidVacation($sort = null)
	{
		if (!$sort) {
			$sort = 'asc';
		}
		$paidVacations = $this->PaidVacation()->orderBy('start_date', $sort)->get();
		foreach ($paidVacations as $key => $paidVacation) {
			if ($paidVacation->start_date > $this->today || $paidVacation->limit_date < $this->today) {
				$paidVacations->forget($key);
			}
		}
		return $paidVacations;
	}

	/**
	 * 有効な有給レコードを取得して、残日数を合算するメソッド
	 * @return type
	 */
	public function getSumRemainingDays()
	{
		$paidVacations = $this->getValidPaidVacation();
		$remainingDays = 0;
		foreach ($paidVacations as $paidVacation) {
			if ($paidVacation->start_date < $this->today && $paidVacation->limit_date > $this->today) {
				$remainingDays += $paidVacation->remaining_days;
			}
		}
		return $remainingDays;
	}

	/**
	 * ユーザの権限用のラベル
	 */
	const ADMIN = 'admin';
	const USER = 'user';

	public static $roleLabels = [
		self::ADMIN => '管理者',
		self::USER => 'ユーザ',
	];

	/**
	 * 有給申請したり、削除したり、編集したりした際に有給残日数を計算してレコードに保存するメソッド
	 * 
	 * @param type $resultRemainingDays 計算後に残っている有給(＝最終的な有給残日数)
	 * 返り値なし
	 */
	public function setRemainingDays($resultRemainingDays)
	{
		$validPaidVacations = $this->getValidPaidVacation(); //有効な有給レコードを取得

		if ($resultRemainingDays >= $validPaidVacations->last()->remaining_days) {
			$resultRemainingDays -= $validPaidVacations->last()->remaining_days;
			$validPaidVacations->first()->remaining_days = $resultRemainingDays;
			$validPaidVacations->first()->save();
		} else {
			$validPaidVacations->last()->remaining_days = $resultRemainingDays;
			$validPaidVacations->first()->remaining_days = 0;
			$validPaidVacations->last()->save();
			$validPaidVacations->first()->save();
		}
	}

}
