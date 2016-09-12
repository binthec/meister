<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\UsedDays;
use App\PaidVacation;
use App\User;

class UseRequestController extends Controller
{

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

			if ($requestedUsedDays > $remainingDays) {
				//残っている有給より大きな値が申請された場合、エラーを返す
				//throw new Exception('申請された日数が有給残日数を上回っています。再度日付を選択してください');
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

		$validPaidVacations = Auth::user()->getValidPaidVacation();
		return view('use_request.add', compact('validPaidVacations'));
	}

	public function edit(Request $request, $id = null)
	{

		if ($request->isMethod('post')) {

			//現在の申請内容を取得（＝これから更新するレコード）
			$data = UsedDays::where('id', $request->id)->first();

			//[既に申請している日数]−[新しく申請する日数]を算出
			$diff_days = $data->used_days - $request->used_days;

			$data->from = $request->from;
			$data->until = $request->until;
//			$data->memo = $request->memo;
			$data->used_days = $request->used_days;
			$data->save();

			//有給残日数を修正
			$user = User::where('id', $request->user_id)->first(); //ユーザIDをもとにユーザインスタンスを生成
			//
			//有給残日数の修正
			if ($diff_days > 0) {//差分が0より多い場合、有給残日数を増やす
				$valid_paid_vacations = $user->getValidPaidVacation('desc'); //ユーザの有効な有給レコードを取得
				foreach ($valid_paid_vacations as $valid_paid_vacation) {
//					dd($valid_paid_vacation);
					while ($valid_paid_vacation->remaining_days > 0 && $diff_days > 0) {
						$valid_paid_vacation->remaining_days++;
						$diff_days--;
					}
					$valid_paid_vacation->save();
					if ($diff_days == 0) {
						break;
					}
				}
			} else {//差分が0より少ない場合、有給残日数を減らす
//				$diff_days = $diff_days * -1;
				$valid_paid_vacations = $user->getValidPaidVacation(); //ユーザの有効な有給レコードを取得
				foreach ($valid_paid_vacations as $valid_paid_vacation) {
//					dd($valid_paid_vacation);
					while ($valid_paid_vacation->remaining_days > 0 && $diff_days < 0) {
						$valid_paid_vacation->remaining_days--;
						$diff_days++;
					}
					$valid_paid_vacation->save();
					if ($diff_days == 0) {
						break;
					}
				}
			}
			return redirect('/use_request'); //一覧ページに戻るときはこっち。
		}

		$useRequest = UsedDays::find($id);
		$usedDays = Auth::user()->usedDays()->paginate(UsedDays::PAGE_NUM); //登録済み有給を取得
		$validPaidVacations = Auth::user()->getValidPaidVacation();
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
