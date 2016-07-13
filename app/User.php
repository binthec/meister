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

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable,
	 CanResetPassword;

	protected $table = 'users';
	protected $fillable = ['email', 'password'];
	protected $hidden = ['password', 'remember_token'];
	public $today;

	const ADMIN = 'admin';
	const USER = 'user';

	public static $roleLabel = [
		self::ADMIN => '管理者',
		self::USER => 'ユーザ',
	];

	public function __construct() {
		$this->today = Carbon::now()->toDateString(); //今日の日付を取得;
	}

	public function paidVacation() {
		return $this->hasMany('App\PaidVacation'); //開始日の若い順に返す
	}

	public function usedDays() {
		return $this->hasMany('App\UsedDays');
	}

	/**
	 * 引数がtrueだったらカーボンのオブジェクトを返す。falseだったら日付('2016-04-07')を返す
	 * @param type $bool
	 * @return type
	 */
	public function getTodayDate($bool = null) {
		if ($bool == true) {
			$today = Carbon::now();
		} else {
			$today = Carbon::now()->toDateString();
		}
		return $today;
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
	 * @param string $sort
	 * @return type
	 */
	public function getValidPaidVacation($sort = null) {
		if (!$sort) {
			$sort = 'asc';
		}
		$paid_vacations = $this->PaidVacation()->orderBy('start_date', $sort)->get();
		foreach ($paid_vacations as $key => $paid_vacation) {
			if ($paid_vacation->start_date > $this->today || $paid_vacation->limit_date < $this->today) {
				$paid_vacations->forget($key);
			}
		}
		return $paid_vacations;
	}

	/**
	 * 有効な有給レコードを取得して、残日数を合算するメソッド
	 * @return type
	 */
	public function addRemainingDays() {
		$paid_vacations = $this->getValidPaidVacation();
		$remaining_days = 0;
		foreach ($paid_vacations as $paid_vacation) {
			if ($paid_vacation->start_date < $this->today && $paid_vacation->limit_date > $this->today) {
				$remaining_days += $paid_vacation->remaining_days;
			}
		}
		return $remaining_days;
	}

//	public function getUsedDays() {
//		return $used_days = $this->usedDays();
//	}
}
