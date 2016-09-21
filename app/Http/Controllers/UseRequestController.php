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

	const MESSAGES = [
		'max' => ':max 文字以内でご入力ください',
	];

	public function index()
	{
		$usedDays = Auth::user()->usedDays()->orderBy('from', 'descs')->paginate(UsedDays::PAGE_NUM); //登録済み有給を取得
		return view('use_request.index', compact('usedDays'));
	}

	public function add(Request $request)
	{
		if ($request->isMethod('post')) {

			$user = User::where('id', $request->user_id)->first(); //ユーザIDをもとにユーザを特定
			$remainingDays = $user->getSumRemainingDays(); //有給残日数の合計
			$requestedUsedDays = $request->used_days; //申請した有給日数

			$validator = Validator::make($request->all(), [
						'memo' => 'max:2000',
							], self::MESSAGES);

			//申請日数が残日数を上回っている時のエラー
			$validator->after(function($validator) use ($requestedUsedDays, $remainingDays) {
				if ($requestedUsedDays > $remainingDays) {
					$validator->errors()->add('daterange', '申請日数が残日数を上回っています。申請日程を確認してください');
				}
			});

			if ($validator->fails()) {
				return redirect()
								->back()
								->withErrors($validator)
								->withInput();
			}

			//申請した有給をUsedDaysテーブルに保存
			$usedDays = new UsedDays;
			$usedDays->user_id = $request->user_id;
			$usedDays->from = $request->from;

			$usedDays->from_am = (isset($request->from_am)) ? 1 : 0;
			$usedDays->from_pm = (isset($request->from_pm)) ? 1 : 0;
			$usedDays->until = $request->until;
			$usedDays->until_am = (isset($request->until_am)) ? 1 : 0;
			$usedDays->used_days = $requestedUsedDays;
			$usedDays->memo = $request->memo;
			$usedDays->save();

			//計算後の有給残日数をレコードに保存
			$resultRemainingDays = $remainingDays - $requestedUsedDays; //有給残日数から申請日数を減算
			$user->setRemainingDays($resultRemainingDays);

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
			$data = UsedDays::where('id', $id)->first();

			$user = User::find(Auth::user()->id);
			$sumRemainingDays = $user->getSumRemainingDays();

			$validator = Validator::make($request->all(), [
						'memo' => 'max:2000',
							], self::MESSAGES);

			//申請日数が残日数を上回っている時のエラー
			$validator->after(function($validator) use ($request, $sumRemainingDays) {
				if ($request->used_days > $sumRemainingDays) {
					$validator->errors()->add('daterange', '申請日数が残日数を上回っています。申請日程を確認してください');
				}
			});

			if ($validator->fails()) {
				return redirect()
								->back()
								->withErrors($validator)
								->withInput();
			}

			//1.既に申請している日数を、合計有給日数に加算して、元に戻す
			$sumRemainingDays += $data->used_days;

			//2.新規で登録する日数を減算
			$resultRemainingDays = $sumRemainingDays - $request->used_days;

			//3.計算後の有給残日数をレコードに保存
			$user->setRemainingDays($resultRemainingDays);

			//編集されたデータで既存レコードを更新
			$data->from = $request->from;
			$data->until = $request->until;
			$data->used_days = $request->used_days;
			$data->memo = $request->memo;
			$data->save();

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
		$usedDays = UsedDays::where('id', $id)->first();
		$usedDays->delete();

		//計算後の有給残日数をレコードに保存
		$user = User::where('id', $usedDays->user_id)->first();
		$resultRemainingDays = $user->getSumRemainingDays() + $usedDays->used_days; //有給残日数に削除する分の申請日数を加算
		$user->setRemainingDays($resultRemainingDays);

		\Session::flash('flashMessage', '申請済有給を削除しました');
		return redirect('/dashboard'); //一覧ページに戻る
	}

}
