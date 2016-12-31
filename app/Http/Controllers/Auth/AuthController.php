<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\PaidVacation;
use Session;
use Carbon\Carbon;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller
{

	use AuthenticatesAndRegistersUsers;

	//ユーザ認証後のリダイレクト先
	protected $redirectPath = '/dashboard';
	protected $redirectTo = '/dashboard';
	//認証されていないユーザのリダイレクト先
	protected $loginPath = '/';
	//ログアウト後のパス
	protected $redirectAfterLogout = '/login';

	public function getLogin()
	{
		return view('auth.login');
	}

	/**
	 * @param Request $request
	 * @return type
	 */
	public function authenticate(Request $request)
	{
		if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
			//ログインの度にDBレコードを更新する
			$user = User::find(Auth::user()->id);
			$user->setOriginalPaidVacations();
			return redirect()->intended('dashboard');
		}
	}

	/**
	 * 登録
	 * 
	 * @param Request $request
	 * @return ユーザ登録画面
	 */
	public function getRegister()
	{
		return view('auth.register');
	}

	/**
	 * 登録実行
	 * 
	 * @param Request $request
	 * @return ユーザ一覧に戻る
	 */
	public function postRegister(Request $request)
	{
		$validator = Validator::make($request->all(), [
					'last_name' => 'required|max:255',
					'first_name' => 'required|max:255',
					'email' => 'required|email|max:255|unique:users',
					'password' => 'required|confirmed|min:8',
					'date_of_entering' => 'required',
		]);
		if ($validator->fails()) {
			return redirect()
							->back()
							->withErrors($validator)
							->withInput();
		}

		$user = new User;
		$user->last_name = $request->last_name;
		$user->first_name = $request->first_name;
		$user->email = $request->email;
		$user->status = $request->status;
		$user->department = $request->department;
		$user->password = bcrypt($request->password);
		$user->date_of_entering = User::getStdDate($request->date_of_entering); //入社日
		$user->base_date = User::getStdDate($request->base_date); //起算日
		$user->role = $request->role;
		$user->memo = $request->memo;
		$user->save();

		//有給の再計算
		$user->setOriginalPaidVacations();

		\Session::flash('flashMsg', 'ユーザ情報を保存しました');
		return redirect('/user'); //一覧ページに戻る
	}

}
