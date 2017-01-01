<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Validator;
use Exception;
use App\UsedDays;
use App\PaidVacation;
use App\User;

class UseRequestController extends Controller
{

	const PAGINATION = 10;

	protected $rules = [
		'used_days' => 'required',
		'memo' => 'max:2000'
	];

	/**
	 * 一覧画面
	 * 
	 * @return type
	 */
	public function index()
	{
		$usedDays = Auth::user()->usedDays()->orderBy('from', 'descs')->paginate(self::PAGINATION); //登録済み有給を取得
		return view('use_request.index', compact('usedDays'));
	}

	/**
	 * 新規登録
	 * 
	 * @return 新規登録画面 
	 */
	public function create()
	{
		$usedDays = Auth::user()->usedDays()->orderBy('from', 'descs')->paginate(self::PAGINATION); //登録済み有給を取得
		$validPaidVacations = Auth::user()->getValidPaidVacation(User::getTodayDate());
		return view('use_request.create', compact('validPaidVacations', 'usedDays', 'useRequest'));
	}

	/**
	 * 登録実行
	 * 
	 * @param Request $request
	 * @return dashboardに戻る
	 */
	public function store(Request $request)
	{
		$user = User::findOrFail($request->user_id);

		//有給残日数の合計を取得
		$sumRemainingDays = $user->getSumRemainingDays();
		//登録する日数を残日数から減算した値（＝登録後の有給残日数）を取得
		$resultRemainingDays = $sumRemainingDays - $request->used_days;
		//前借りしている場合は、前借り分も計算後の残日数から減算する
		$resultRemainingDays = $user->getResultRemainingDays($resultRemainingDays);

		$validator = Validator::make($request->all(), $this->rules);
		$validator->after(function($validator) use ($resultRemainingDays, $user, $request) {
			//登録日数が前借り可能日数すら上回っている時のエラー
			if (($user->getAdvancedPaidVacaion()->original_paid_vacation + $resultRemainingDays) < 0) {
				$validator->errors()->add('daterange', '指定した日数は前借り可能日数を上回っています。日程を確認してください。');
			}
			//取得日が入力されていない場合のエラー
			if ($validator->errors()->has('used_days')) {
				$validator->errors()->add('daterange', $validator->errors()->first('used_days'));
			}
			//指定した期間が登録済み有給期間と重複している場合のエラー
			if ($user->checkDateDuplication($request->from, $request->until)) {
				$validator->errors()->add('daterange', '指定した期間は登録済みの有給期間と重複しています。日程を確認してください。');
			}
		});
		if ($validator->fails()) {
			return redirect()
							->back()
							->withErrors($validator)
							->withInput();
		}

		DB::beginTransaction();

		try {
			//計算後の有給残日数をレコードに保存
			$user->setRemainingDays($resultRemainingDays);

			//登録した有給の内容をUsedDaysテーブルに保存
			$usedDays = new UsedDays;
			$usedDays->user_id = $request->user_id;
			$usedDays->from = $request->from;
			$usedDays->from_am = (isset($request->from_am)) ? 1 : 0;
			$usedDays->from_pm = (isset($request->from_pm)) ? 1 : 0;
			$usedDays->until = $request->until;
			$usedDays->until_am = (isset($request->until_am)) ? 1 : 0;
			$usedDays->used_days = $request->used_days;
			$usedDays->memo = $request->memo;
			$usedDays->save();
			DB::commit();

			return redirect('/dashboard')->with('flashMsg', '有給消化登録が完了しました。');
		} catch (\Exception $e) {
			DB::rollback();
			Log::error($e->getMessage());
			return redirect('/dashboard')->with('flashErrMsg', '有給消化登録に失敗しました。時間をおいて再度お試しください。');
		}
	}

	/**
	 * 編集
	 * 
	 * @param str $id
	 * @return 編集画面
	 */
	public function edit($id)
	{
		$useRequest = UsedDays::find($id);
		$usedDays = Auth::user()->usedDays()->orderBy('from', 'descs')->paginate(self::PAGINATION); //登録済み有給を取得
		$validPaidVacations = Auth::user()->getValidPaidVacation(User::getTodayDate());
		return view('use_request.edit', compact('useRequest', 'usedDays', 'validPaidVacations'));
	}

	/**
	 * 編集実行
	 * 
	 * @param Request $request
	 * @param str $id
	 * @return dashboardに戻る
	 */
	public function update(Request $request, $id)
	{
		//現在の登録内容を取得（＝これから更新するレコード）
		$usedDays = UsedDays::find($id);
		$user = User::find(Auth::user()->id);

		//有給残日数の合計を取得
		$sumRemainingDays = $user->getSumRemainingDays();

		//1.既に登録している日数を、合計有給日数に加算して、元に戻す
		$sumRemainingDays += $usedDays->used_days;

		//2.新規で登録する日数を、残日数から減算
		$resultRemainingDays = $sumRemainingDays - $request->used_days;

		//3.前借りしている場合は、前借り分も計算後の残日数から減算する
		$resultRemainingDays = $user->getResultRemainingDays($resultRemainingDays);

		$validator = Validator::make($request->all(), $this->rules);
		$validator->after(function($validator) use ($resultRemainingDays, $user, $request, $usedDays) {
			//登録日数が前借り可能日数すら上回っている時のエラー
			if (($user->getAdvancedPaidVacaion()->original_paid_vacation + $resultRemainingDays) < 0) {
				$validator->errors()->add('daterange', '登録日数が前借り可能日数を上回っています。登録日程を確認してください');
			}
			//登録日が入力されていない場合のエラー
			if ($validator->errors()->has('used_days')) {
				$validator->errors()->add('daterange', $validator->errors()->first('used_days'));
			}
			//指定した期間が登録済み有給期間と重複している場合のエラー
			if ($user->checkDateDuplication($request->from, $request->until, $usedDays)) {
				$validator->errors()->add('daterange', '指定した期間は登録済みの有給期間と重複しています。日程を確認してください');
			}
		});
		if ($validator->fails()) {
			return redirect()
							->back()
							->withErrors($validator)
							->withInput();
		}

		DB::beginTransaction();

		try {
			//3.計算後の有給残日数をレコードに保存
			$user->setRemainingDays($resultRemainingDays);

			//編集されたデータで既存レコードを更新
			$usedDays->from = $request->from;
			$usedDays->from_am = (isset($request->from_am)) ? 1 : 0;
			$usedDays->from_pm = (isset($request->from_pm)) ? 1 : 0;
			$usedDays->until = $request->until;
			$usedDays->until_am = (isset($request->until_am)) ? 1 : 0;
			$usedDays->used_days = $request->used_days;
			$usedDays->memo = $request->memo;
			$usedDays->save();
			DB::commit();

			return redirect('/dashboard')->with('flashMsg', '登録済有給の編集が完了しました');
		} catch (\Exception $e) {
			DB::rollback();
			Log::error($e->getMessage());
			return redirect('/dashboard')->with('flashErrMsg', '有給消化編集に失敗しました。時間をおいて再度お試しください。');
		}
	}

	/**
	 * 削除実行
	 * 
	 * @param str $id
	 * @return dashboardに戻る
	 */
	public function destroy($id)
	{
		$usedDays = UsedDays::find($id);
		$usedDays->delete();

		$user = User::find($usedDays->user_id);
		//有給残日数に、削除する分の登録日数を加算して元に戻す
		$resultRemainingDays = $user->getSumRemainingDays() + $usedDays->used_days;
		//前借りしている場合は、前借り分を計算後の残日数から減算する
		$resultRemainingDays = $user->getResultRemainingDays($resultRemainingDays);
		//最終的な計算後の残日数をもとに、有給残日数を計算してレコードを更新する
		$user->setRemainingDays($resultRemainingDays);

		return redirect('/dashboard')->with('flashMsg', '登録済有給を削除しました');
	}

}
