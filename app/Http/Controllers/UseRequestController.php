<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use Exception;
use App\UsedDays;
use App\PaidVacation;
use App\User;

class UseRequestController extends Controller
{

	protected $rules = [
		'used_days' => 'required',
		'memo' => 'max:2000'
	];

	public function index()
	{
		$usedDays = Auth::user()->usedDays()->orderBy('from', 'descs')->paginate(UsedDays::PAGE_NUM); //登録済み有給を取得
		return view('use_request.index', compact('usedDays'));
	}

	public function add(Request $request)
	{
		if ($request->isMethod('post')) {

//			dd($request->from);
			//ユーザIDをもとにユーザを特定
			$user = User::where('id', $request->user_id)->first();
			//有給残日数の合計を取得
			$sumRemainingDays = $user->getSumRemainingDays();
			//登録する日数を残日数から減算した値（＝登録後の有給残日数）を取得
			$resultRemainingDays = $sumRemainingDays - $request->used_days;
			//前借りしている場合は、前借り分も計算後の残日数から減算する
			$resultRemainingDays = $user->getResultRemainingDays($resultRemainingDays);

			$validator = Validator::make($request->all(), $this->rules);
			$validator->after(function($validator) use ($resultRemainingDays, $user, $request) {
				//申請日数が前借り可能日数すら上回っている時のエラー
				if (($user->getAdvancedPaidVacaion()->original_paid_vacation + $resultRemainingDays) < 0) {
					$validator->errors()->add('daterange', '指定した日数は前借り可能日数を上回っています。日程を確認してください');
				}
				//申請日が入力されていない場合のエラー
				if ($validator->errors()->has('used_days')) {
					$validator->errors()->add('daterange', $validator->errors()->first('used_days'));
				}
				//指定した期間が登録済み有給期間と重複している場合のエラー
				if ($user->checkDateDuplication($request->from, $request->until)) {
					$validator->errors()->add('daterange', '指定した期間は登録済みの有給期間と重複しています。日程を確認してください');
				}
			});

			if ($validator->fails()) {
				return redirect()
								->back()
								->withErrors($validator)
								->withInput();
			}

			//計算後の有給残日数をレコードに保存
			$user->setRemainingDays($resultRemainingDays);

			//申請した有給の内容をUsedDaysテーブルに保存
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

			\Session::flash('flashMessage', '有給消化申請が完了しました');
			return redirect('/dashboard'); //一覧ページに戻る
		}

		$validPaidVacations = Auth::user()->getValidPaidVacation(User::getTodayDate());
		return view('use_request.add', compact('validPaidVacations'));
	}

	public function edit(Request $request, $id)
	{

		if ($request->isMethod('post')) {

			//現在の申請内容を取得（＝これから更新するレコード）
			$usedDays = UsedDays::find($id);
			$user = User::find(Auth::user()->id);

			//有給残日数の合計を取得
			$sumRemainingDays = $user->getSumRemainingDays();

			//1.既に申請している日数を、合計有給日数に加算して、元に戻す
			$sumRemainingDays += $usedDays->used_days;

			//2.新規で登録する日数を、残日数から減算
			$resultRemainingDays = $sumRemainingDays - $request->used_days;

			//3.前借りしている場合は、前借り分も計算後の残日数から減算する
			$resultRemainingDays = $user->getResultRemainingDays($resultRemainingDays);

			$validator = Validator::make($request->all(), $this->rules);
			$validator->after(function($validator) use ($resultRemainingDays, $user, $request) {
				//申請日数が前借り可能日数すら上回っている時のエラー
				if (($user->getAdvancedPaidVacaion()->original_paid_vacation + $resultRemainingDays) < 0) {
					$validator->errors()->add('daterange', '申請日数が前借り可能日数を上回っています。申請日程を確認してください');
				}
				//申請日が入力されていない場合のエラー
				if ($validator->errors()->has('used_days')) {
					$validator->errors()->add('daterange', $validator->errors()->first('used_days'));
				}
				//指定した期間が登録済み有給期間と重複している場合のエラー
				if ($user->checkDateDuplication($request->from, $request->until)) {
					$validator->errors()->add('daterange', '指定した期間は登録済みの有給期間と重複しています。日程を確認してください');
				}
			});

			if ($validator->fails()) {
				return redirect()
								->back()
								->withErrors($validator)
								->withInput();
			}

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

			\Session::flash('flashMessage', '申請済有給の編集が完了しました');
			return redirect('/dashboard');
		}

		$useRequest = UsedDays::find($id);
		$usedDays = Auth::user()->usedDays()->paginate(UsedDays::PAGE_NUM); //登録済み有給を取得
		$validPaidVacations = Auth::user()->getValidPaidVacation(User::getTodayDate());
		return view('use_request.edit', compact('useRequest', 'usedDays', 'validPaidVacations'));
	}

	public function delete($id)
	{
		$usedDays = UsedDays::find($id);
		$usedDays->delete();

		$user = User::find($usedDays->user_id);
		//有給残日数に、削除する分の申請日数を加算して元に戻す
		$resultRemainingDays = $user->getSumRemainingDays() + $usedDays->used_days;
		//前借りしている場合は、前借り分を計算後の残日数から減算する
		$resultRemainingDays = $user->getResultRemainingDays($resultRemainingDays);
		//最終的な計算後の残日数をもとに、有給残日数を計算してレコードを更新する
		$user->setRemainingDays($resultRemainingDays);

		\Session::flash('flashMessage', '申請済有給を削除しました');
		return redirect('/dashboard'); //一覧ページに戻る
	}

}
