<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\PaidVacation;
use App\UsedDays;
use Session;
use Carbon\Carbon;

class UserController extends Controller
{

	public $today;

	public function __construct()
	{
		$this->today = Carbon::now()->toDateString(); //今日の日付を取得;
	}

	public function index()
	{
		$users = User::all();
		return view('user.index', ['users' => $users, 'today' => $this->today]);
	}

	public function create()
	{
		//
	}

	public function usedList()
	{
//		$user = Auth::user();
//		$used_days = UsedDays::where('user_id', $user->id)->get();
		return view('user.used_list');
	}

	public function edit(Request $request, $id = null)
	{
		$user = User::find($id);

		if ($request->isMethod('post')) {

			$user->last_name = $request->last_name;
			$user->first_name = $request->first_name;
			$user->email = $request->email;
			$user->date_of_entering = User::getStdDate($request->date_of_entering); //入社日
			$user->base_date = User::getStdDate($request->base_date); //起算日
			$user->memo = $request->memo;
			$user->save();

			$today = User::getTodayDate(false); //今日の日付を取得
			$date_of_entering = $user->date_of_entering; //入社日
			$base_date = $user->base_date; //起算日

			$paid_vacations = PaidVacation::where('user_id', $id)->get(); //編集するユーザIDを持つ有給レコードを取得
			foreach ($paid_vacations as $paid_vacation) { //レコードが存在する場合、一旦物理削除
				$paid_vacation->delete();
			}


			//有給の再計算、データ生成後にレコード保存
			$calc = new PaidVacation;
			$calc->calcRemainingDays($user->date_of_entering, $user->base_date, $user->id);

			\Session::flash('flashMessage', 'ユーザ情報を保存しました');
			return redirect('/user'); //一覧ページに戻るときはこっち。
		}

		return view('user.edit', ['user' => $user]);
	}

	public function show($id)
	{
		$user = User::where('id', $id)->first();
		return view('user.profile', ['user' => $user]);
	}

	public function reset($id = null)
	{
		$user = User::find($id);

		$paid_vacations = PaidVacation::where('user_id', $id)->get(); //編集するユーザIDを持つ有給レコードを取得
		foreach ($paid_vacations as $paid_vacation) { //レコードが存在する場合、一旦物理削除
			$paid_vacation->delete();
		}

		//有給の再計算、データ生成後にレコード保存
		$calc = new PaidVacation;
		$calc->calcRemainingDays($user->date_of_entering, $user->base_date, $user->id);

		$usedDays = UsedDays::where('user_id', Auth::user()->id)->delete();

		\Session::flash('flashMessage', 'リセット完了');
		return redirect()->back(); //再計算後はdashboardに戻る
	}

}
