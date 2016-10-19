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

	public function devices()
	{
		return $this->hasMany('App\Device');
	}

	/**
	 * 引数がtrueだったらカーボンのオブジェクトを返す。引数なし、またはfalseだったら日付 'Y-m-d' を返す
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

	/**
	 * 付与されている有給日数の計算
	 * @param type $baseDate 起算日
	 * @param type $startDate
	 * @return int
	 */
	public function getOriginalPaidVacation($baseDate, $startDate)
	{
		$baseDate = Carbon::createFromFormat('Y-m-d', $baseDate);
		$startDate = Carbon::createFromFormat('Y-m-d', $startDate);

		//$years = $start_date->diffInYears($base_date);
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
	}

	/**
	 * 入社してから今日日付までに付与されている有給を計算して、レコードに存在しない部分を追加するメソッド
	 * 何もレコードを持っていないユーザの場合は全て新規作成になる
	 * 
	 * @param int $userId
	 * 返り値なし
	 */
	public function setOriginalPaidVacations()
	{

		$paidVacation = PaidVacation::where('user_id', $this->id)->orderBy('start_date', 'desc')->get();

		if ($paidVacation->count() > 0) {
			//既に登録されている有給レコードが存在する場合、最新のレコードの有給期限開始日を取得して代入
			$startDate = Carbon::createFromFormat('Y-m-d', $paidVacation->first()->start_date)->addYear(1)->toDateString();
		} else {
			//登録されている有給レコードが存在しないので、userテーブルにある起算日を代入
			$startDate = User::where('id', $this->id)->first()->base_date;
		}

		//起算日
		$baseDate = User::where('id', $this->id)->first()->base_date;

		//前借り機能のため、有給レコードはデフォルトで１年分多く作成しておく
		$limitDate = self::getTodayDate(true)->addYears(1)->toDateString();
		while ($limitDate > $startDate) {
			$paidVacation = new PaidVacation; //新規にインスタンス生成
			$paidVacation->user_id = $this->id;

			$paidVacation->start_date = $startDate;
			$paidVacation->limit_date = Carbon::createFromFormat('Y-m-d', $startDate)->addYear(2)->subDay(); //起算日から2年後マイナス1日が期限日
			$paidVacation->remaining_days = $this->getOriginalPaidVacation($baseDate, $startDate); //有給日数を取得：有給取得によって減算されるカラム
			$paidVacation->original_paid_vacation = $this->getOriginalPaidVacation($baseDate, $startDate); //有給日数を取得：本来の日数を記憶しておくためのカラム。減算されない。
			$paidVacation->save();

			$startDate = Carbon::createFromFormat('Y-m-d', $startDate)->addYear(1)->toDateString(); //有給の有効期限開始日
		}
	}

	/**
	 * 基準日（＝$baseDate）を含む有給レコードのコレクションを返すメソッド
	 * 今日日付を渡せば、今日時点で有効な有給レコードが返る
	 * 
	 * @param type $baseDate 'Y-m-d' 形式の日付。
	 * @param string $sort 省略した場合は早く期限が切れる順（＝古い順'asc'）にコレクションを返す
	 * @return collection $baseDateを含む２つのモデルがコレクションになって返る
	 */
	public function getValidPaidVacation($baseDate, $sort = 'asc')
	{
		$paidVacations = $this->PaidVacation()->orderBy('start_date', $sort)->get();
		foreach ($paidVacations as $key => $paidVacation) {
			//期限開始日がbaseDateより大きいか、期限日がbaseDateよりも小さい場合は、Collectionからはずす
			if ($paidVacation->start_date > $baseDate || $paidVacation->limit_date < $baseDate) {
				$paidVacations->forget($key);
			}
		}
		return $paidVacations;
	}

	/**
	 * 有効な有給レコードを取得して、残日数を合算するメソッド
	 * 
	 * @param type $baseDate 'Y-m-d'の日付。省略した場合は今日日付で有効な有給の合算を出す
	 * @return type
	 */
	public function getSumRemainingDays($baseDate = null)
	{
		if (!$baseDate) {
			$baseDate = self::getTodayDate();
		}
		$paidVacations = $this->getValidPaidVacation($baseDate);
		$remainingDays = 0;
		foreach ($paidVacations as $paidVacation) {
			if ($paidVacation->start_date < $baseDate && $paidVacation->limit_date > $baseDate) {
				$remainingDays += $paidVacation->remaining_days;
			}
		}
		return $remainingDays;
	}

	/**
	 * 有給申請したり、削除したり、編集したりした際に有給残日数を計算してレコードを更新するメソッド
	 * 
	 * @param int $resultRemainingDays は申請日数分を「減算した”後”」の有給残日数
	 * @param date $baseData 'Y-m-d'の日付。省略した場合は今日日付で有効なレコードの値をもとにする
	 * 返り値なし
	 */
	public function setRemainingDays($resultRemainingDays, $baseDate = null)
	{
		if (!$baseDate) {
			$baseDate = self::getTodayDate();
		}


		//前借り用レコード（＝前借りしてくるための未来の有給レコード）を取得
		$advancedPaidVacation = $this->getAdvancedPaidVacaion();

		//前借りしている状態の場合、前借りしている分を計算後の日数から減算する
//		$diff = $this->getUsedAdvancedDays();
//		if ($diff > 0) {
//			$resultRemainingDays -= $diff;
//		}
//		dd($resultRemainingDays);
		//計算後の有給残日数が0以下の場合、前借り処理を行う
		if ($resultRemainingDays <= 0) {

			//前借り用のレコードに計算後の数字（=マイナスの値）を加算
			$advancedPaidVacation->remaining_days = ($advancedPaidVacation->original_paid_vacation + $resultRemainingDays);

			//最終的な残日数が0以上であれば前借り処理を実行
			if ($advancedPaidVacation->remaining_days >= 0) {
				$advancedPaidVacation->save(); //保存
				//有効な有給レコードを取得
				$validPaidVacations = $this->getValidPaidVacation($baseDate);
				//現在有効なレコード全てに残日数0を代入して保存
				foreach ($validPaidVacations as $validPaidVacation) {
					$validPaidVacation->remaining_days = 0;
					$validPaidVacation->save();
				}
			}
		} else {//有効な有給レコードから通常の減算をする
			//計算後の残日数が１以上の場合は、前借り用のレコードの残日数の値を元に戻す
			$advancedPaidVacation->remaining_days = $advancedPaidVacation->original_paid_vacation;
			$advancedPaidVacation->save();

			//有効な有給レコードを取得
			$validPaidVacations = $this->getValidPaidVacation($baseDate);
			if ($validPaidVacations->count() == 1) {
				//まだ入社して２年半未満の人
				$validPaidVacations->first()->remaining_days = $resultRemainingDays;
				$validPaidVacations->first()->save();
			} elseif ($resultRemainingDays >= $validPaidVacations->last()->original_paid_vacation) {
				$resultRemainingDays -= $validPaidVacations->last()->original_paid_vacation;
				$validPaidVacations->last()->remaining_days = $validPaidVacations->last()->original_paid_vacation;
				$validPaidVacations->first()->remaining_days = $resultRemainingDays;
				$validPaidVacations->last()->save();
				$validPaidVacations->first()->save();
			} else {
				$validPaidVacations->last()->remaining_days = $resultRemainingDays;
				$validPaidVacations->first()->remaining_days = 0;
				$validPaidVacations->last()->save();
				$validPaidVacations->first()->save();
			}
		}
	}

	/**
	 * 既に登録されている有給消化申請の日数を、
	 * 有給レコード（オリジナルのまっさらな状態のもの）から減算していく
	 * 入社日を変更した場合など、全部の再計算が必要な際に行う
	 * 
	 * @param type $userId
	 */
	public function recalcRemainingDays()
	{
		$usedDays = UsedDays::where('user_id', $this->id)->orderBy('from', 'asc')->get();
		foreach ($usedDays as $day) {
			$sum = $this->getSumRemainingDays($day->start_date);
			$resultRemainingDays = $sum - $day->used_days;
			//前借りしている場合は、前借り分も計算後の残日数から減算する
			if ($this->getUsedAdvancedDays() > 0) {
				$resultRemainingDays -= $this->getUsedAdvancedDays();
			}
			$this->setRemainingDays($resultRemainingDays, $day->start_date);
		}
	}

	/**
	 * //前借り用レコード（＝前借りしてくるための未来の有給レコード）を取得するメソッド
	 * 
	 * @return type PaidVacation
	 */
	public function getAdvancedPaidVacaion()
	{
		return $this->PaidVacation()->orderBy('start_date', 'desc')->first();
	}

	/**
	 * 前借りしている日数を返すメソッド
	 *  
	 * @param PaidVacation $invalidPaidVacation
	 * @return type int
	 */
	public function getUsedAdvancedDays()
	{
		$advancedPaidVacation = $this->getAdvancedPaidVacaion(); //前借り用レコード取得
		return $advancedPaidVacation->original_paid_vacation - $advancedPaidVacation->remaining_days;
	}

	/**
	 * 申請した日数と合わせて、最終的な計算後の残日数を出す関数がやっぱいるわな……
	 * 
	 */
	public function getResultRemainingDays()
	{
		//
	}

	/**
	 * ユーザの権限用のラベル
	 */
	const USER = 20;
	const ADMIN = 1;

	public static $roleLabels = [
		self::USER => 'ユーザ',
		self::ADMIN => '管理者',
	];

	/**
	 * 社員区分
	 */
	const REGULAR_EMPLOYEE = 1;
	const CONTRACT_EMPLOYEE = 2;
	const DIRECTOR = 50;

	public static $memberStatus = [
		self::REGULAR_EMPLOYEE => '正社員',
		self::CONTRACT_EMPLOYEE => '契約社員',
		self::DIRECTOR => '役員',
	];

	/**
	 * 部署
	 */
	const NO_DEPARTMENT = 0;
	const DEPARTMENT_SYSTEM = 1;
	const DEPARTMENT_FRONT = 2;
	const GENERAL_AFFAIRS = 50;

	public static $departments = [
		self::NO_DEPARTMENT => '部署なし',
		self::DEPARTMENT_SYSTEM => 'システムチーム',
		self::DEPARTMENT_FRONT => 'フロントチーム',
		self::GENERAL_AFFAIRS => '総務',
	];

}
