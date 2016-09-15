<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Route;
use App\User;
use App\PaidVacation;
use App\UsedDays;
use Session;
use Carbon\Carbon;

class UserController extends Controller
{

	const MESSAGES = [
		'last_name.required' => '名字は必須項目です',
		'last_name.max' => '苗字は最大 :max 文字以内で入力してください',
		'first_name.required' => '名前は必須項目です',
		'first_name.max' => '名前は最大 :max 文字以内で入力してください',
		'email.required' => 'Eメールアドレスを入力してください',
		'email.email' => '正しいメールアドレスを入力してください',
		'email.max' => 'メールアドレスは :max 文字以内で入力してください',
		'email.unique' => '入力したメールアドレスは既に使われています',
		'password.required' => 'パスワードは必須項目です',
		'password.min' => 'パスワードは :min 字以上で入力してください',
		'password.confirmed' => '入力したパスワードが一致しません',
		'password_confirmation.required_with' => '確認用パスワードを入力してください',
		'date_of_entering.required' => '入社日は必須項目です',
	];

	protected $today;

	public function __construct()
	{
		$this->today = Carbon::now()->toDateString(); //今日の日付を取得;
	}

	public function index()
	{
		$users = User::all();
		return view('user.index', ['users' => $users, 'today' => $this->today]);
	}

	/**
	 * ユーザ個人のプロフィール画面へ遷移
	 * 
	 * @param type $userId
	 */
	public function profile($userId)
	{
		$user = User::find($userId);
		$validPaidVacations = $user->getValidPaidVacation(User::getTodayDate());
		$usedDays = $user->usedDays()->orderBy('from', 'descs')->paginate(UsedDays::PAGE_NUM); //登録済み有給を取得
		return view('user.profile', compact('user', 'validPaidVacations', 'usedDays'));
	}

	/**
	 * ユーザの詳細情報変更メソッド
	 * 
	 * @param Request $request
	 * @param type $id
	 * @return ユーザのプロフィール画面へ遷移
	 */
	public function editPofile(Request $request, $userId)
	{
		$user = User::find($userId);

		if ($request->isMethod('post')) {

			//バリデーションエラーがあればエラーを返す
			$validator = Validator::make($request->all(), [
						'last_name' => 'required|max:255',
						'first_name' => 'required|max:255',
						'email' => 'required|email|max:255|unique:users,email,' . $userId,
						'password' => 'confirmed|min:8',
						'password_confirmation' => 'required_with:password',
							], self::MESSAGES);

			if ($validator->fails()) {
				return redirect()
								->back()
								->withErrors($validator)
								->withInput();
			}

			$user->last_name = $request->last_name;
			$user->first_name = $request->first_name;
			$user->email = $request->email;
			$user->status = $request->status;
			$user->department = $request->department;
			$user->role = $request->role;
			$user->retire_flg = (isset($request->retire_flg)) ? $request->retire_flg : null;
			$user->memo = $request->memo;
			$user->save();

			\Session::flash('flashMessage', 'ユーザ情報の変更を完了しました');
			return redirect()->action('UserController@profile', $userId);
		}

		return view('user.editProfile', compact('user'));
	}

	/**
	 * 入社日変更メソッド
	 * 
	 * @param Request $request
	 * @param type $id
	 */
	public function editDate(Request $request, $useId)
	{
		$user = User::find($useId);

		if ($request->isMethod('post')) {

			$user->date_of_entering = User::getStdDate($request->date_of_entering); //入社日
			$user->base_date = User::getStdDate($request->base_date); //起算日
			$user->save();

			//1.編集するユーザIDを持つ有給レコードを物理削除
			PaidVacation::where('user_id', $useId)->delete();

			//2.有給を最初から再計算して、新規に、現在までの有給レコード生成
			PaidVacation::setOriginalPaidVacations($useId);

			//3.既に登録されている有給消化申請の日数を有給レコードから減算
			PaidVacation::recalcRemainingDays($useId);

			\Session::flash('flashMessage', '入社日の変更を完了しました');
			return redirect()->action('UserController@profile', $userId);
		}

		return view('user.editDate', ['user' => $user]);
	}

	public function reset($id = null)
	{
		$user = User::find($id);

		$paid_vacations = PaidVacation::where('user_id', $id)->get(); //編集するユーザIDを持つ有給レコードを取得
		foreach ($paid_vacations as $paid_vacation) { //レコードが存在する場合、一旦物理削除
			$paid_vacation->delete();
		}

		//有給の再計算、データ生成後にレコード保存
		PaidVacation::setOriginalPaidVacations($user->id);

		$usedDays = UsedDays::where('user_id', Auth::user()->id)->delete();

		\Session::flash('flashMessage', 'リセット完了');
		return redirect()->back(); //再計算後はdashboardに戻る
	}

	/**
	 * PaidVavationテーブルを、今日日付の段階での最新にするためのメソッド
	 * 
	 * @param type $userId 引数は、ユーザIDが渡れば単一のユーザのレコード更新、ユーザIDが無ければ全ユーザのレコードを更新する
	 * @return 個人が更新をした場合はdashboard、管理者が全体に対してDB更新した場合はユーザ一覧にリダイレクト
	 */
	public function update($userId = null)
	{

		if ($userId) {
			$users = User::where('id', $userId)->get();
			$url = '/dashboard';
		} else {
			$users = User::all();
			$url = '/user';
		}

		foreach ($users as $user) {
			PaidVacation::setOriginalPaidVacations($user->id);
		}

		\Session::flash('flashMessage', 'DBレコードのアップデートを完了しました');
		return redirect($url);
	}

}
