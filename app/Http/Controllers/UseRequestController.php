<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\UsedDays;

class UseRequestController extends Controller {

	public function useRequest(Request $request) {

		if ($request->isMethod('post')) {
			$used_days = new UsedDays;
			$used_days->user_id = $request->user_id;
			$used_days->from = $request->from;
			$used_days->until = $request->until;
			$used_days->used_days = $request->used_days;
			$used_days->save();

			//登録された消化分の有給日数を、有給残日数から差し引く処理
			$used_days = $request->used_days; //登録日数
			$user_id = $request->user_id; //ユーザID
			$user = User::where('id', $user_id)->first(); //ユーザIDをもとにユーザインスタンスを生成
			$valid_paid_vacations = $user->getValidPaidVacation(); //ユーザの有効な有給レコードを取得

			foreach ($valid_paid_vacations as $valid_paid_vacation) {
//				dd($valid_paid_vacation);
				while ($valid_paid_vacation->remaining_days > 0 && $used_days > 0) {
					$valid_paid_vacation->remaining_days--;
					$used_days--;
				}
				$valid_paid_vacation->save();
				if ($used_days == 0) {
					break;
				}
			}

			//dd($valid_paid_vacations);


			\Session::flash('flash_message', 'ユーザ情報を保存しました');
			//return redirect()->back(); //編集ページに留まる時はこっち。
			return redirect('/user/use_request'); //一覧ページに戻るときはこっち。
		}
		$used_days = Auth::user()->usedDays()->paginate(5); //登録済み有給を取得
		return view('use_request.use_request', ['used_days' => $used_days]);
	}

	public function requestEdit(Request $request, $id = null) {
		//

		if ($request->isMethod('post')) {
			$new_used_days = UsedDays::where('id', $request->id)->first();
			$new_used_days->from = $request->from;
			$new_used_days->until = $request->until;
//			$new_used_days->memo = $request->memo;
			$new_used_days->used_days = $request->used_days;
			$new_used_days->save();
			return redirect('/user/use_request'); //一覧ページに戻るときはこっち。
		}
		$requested = UsedDays::where('id', $id)->first();

		$used_days = Auth::user()->usedDays()->paginate(5); //登録済み有給を取得
		return view('use_request.request_edit', ['used_days' => $used_days, 'requested' => $requested]);
	}

}