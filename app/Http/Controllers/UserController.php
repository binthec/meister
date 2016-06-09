<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Http\RedirectResponse;
//use App\Http\Requests\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\PaidVacation;
use App\UsedDays;
use Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller {

	public $today;

	public function __construct() {
		$this->today = Carbon::now()->toDateString(); //今日の日付を取得;
	}

	public function index() {
		$users = User::all();
		return view('user.index', ['users' => $users, 'today' => $this->today]);
	}

	public function create() {
		//
	}

	public function usedList() {
//		$user = Auth::user();
//		$used_days = UsedDays::where('user_id', $user->id)->get();
		return view('user.used_list');
	}

	public function edit(Request $request, $id = null) {

		if ($request->isMethod('post')) {
			$user = User::find($id);
			$user->family_name = $request->family_name;
			$user->first_name = $request->first_name;
			$user->email = $request->email;
			$user->date_of_entering = $request->date_of_entering; //入社日
			$user->base_date = $request->base_date; //起算日
			$user->memo = $request->memo;
			$user->save();

			$today = Carbon::now()->toDateString(); //今日の日付を取得
			$date_of_entering = $request->date_of_entering; //入社日
			$base_date = $request->base_date; //起算日

			$paid_vacations = PaidVacation::where('user_id', $id)->get(); //編集するユーザIDを持つ有給レコードを取得
			foreach ($paid_vacations as $paid_vacation) { //レコードが存在する場合、一旦物理削除
				$paid_vacation->delete();
			}

			//有給の再計算
			$calc = new PaidVacation;
			$calc->calcRemainingDays($request->date_of_entering, $request->base_date, $user->id);

			\Session::flash('flash_message', 'ユーザ情報を保存しました');
			//return redirect()->back(); //編集ページに留まる時はこっち。
			return redirect('/user'); //一覧ページに戻るときはこっち。
		}
		$user = User::find($id);
		return view('user.edit', ['user' => $user]);
	}

	public function store(Request $request) {
		//
	}

	public function show($id) {
		//
	}

	public function update(Request $request, $id) {
		//
	}

	public function destroy($id) {
		//
	}

}
