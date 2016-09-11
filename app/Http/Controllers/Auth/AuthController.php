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

class AuthController extends Controller
{

	use AuthenticatesAndRegistersUsers;

	//ユーザ認証後のリダイレクト先
	protected $redirectPath = '/dashboard';
	protected $redirectTo = '/dashboard';
	//認証されていないユーザのリダイレクト先
	protected $loginPath = '/login';
	//ログアウト後のパス
	protected $redirectAfterLogout = '/login';

	public function __construct()
	{
		//$this->middleware('guest', ['except' => 'getLogout']);
		$this->middleware('guest', ['except' => ['getLogout', 'register']]);
	}

	protected function validator(Request $request)
	{

		$messages = [
			'last_name.required' => '苗字は必須項目です。',
			'last_name.max' => '苗字は最大 :max 文字以内で入力してください。。',
			'first_name.required' => '名前は必須項目です。',
			'first_name.max' => '名前は最大 :max 文字以内で入力してください。。',
			'email.required' => 'Eメールアドレスを入力してください。',
			'email.email' => '正しいEメールアドレスを入力してください。',
			'email.max' => 'メールアドレスは :max 文字以内で入力してください。',
			'email.unique' => '入力したメールアドレスは既に使われています。',
			'password.required' => 'パスワードは必須項目です。',
			'password.min' => 'パスワードは :min 字以上で入力してください。',
			'password.confirmed' => '入力したパスワードが一致しません。',
			'date_of_entering.required' => '入社日は必須項目です。',
		];

		return Validator::make($request->all(), [
					'last_name' => 'required|max:255',
					'first_name' => 'required|max:255',
					'email' => 'required|email|max:255|unique:users',
					'password' => 'required|confirmed|min:8',
					'date_of_entering' => 'required',
						], $messages);
	}

	public function getLogin()
	{
		return view('auth.login');
	}

	public function register(Request $request)
	{

		if ($request->isMethod('post')) {

			//バリデーションエラーがあればエラーを返す
//			$val = $this->validator($request);
//			if ($val->fails()) {
//				return redirect('auth/register')
//								->withErrors($val)
//								->withInput();
//			}
//			
			//エラーがなければUserインスタンスを新規作成
			$user = new User;
			$user->role = $request->role;
			$user->last_name = $request->last_name;
			$user->first_name = $request->first_name;
			$user->email = $request->email;
			$user->password = bcrypt($request->password);
			$user->date_of_entering = User::getStdDate($request->date_of_entering); //入社日
			$user->base_date = User::getStdDate($request->base_date); //起算日
			$user->memo = $request->memo;
			$user->save();

			//有給の再計算
			$calc = new PaidVacation;
			$calc->calcRemainingDays($user->date_of_entering, $user->base_date, $user->id);

//			//有給の計算
//			$today = Carbon::now()->toDateString(); //今日の日付を取得
//			$date_of_entering = $request->date_of_entering; //入社日
//			$base_date = $request->base_date; //起算日
//			$start_date = $request->base_date; //起算日を最初の有給の有効期限開始日に格納
//
//
//			$j = 0;
//			while ($today > $start_date) {
//				$paid_vacations = new PaidVacation; //新規にインスタンス生成
//				$paid_vacations->user_id = $user->id;
//
//				$start_date = Carbon::createFromFormat('Y-m-d', $start_date)->addYear($j)->toDateString(); //有給の有効期限開始日
//				$paid_vacations->start_date = $start_date;
//				$paid_vacations->limit_date = Carbon::createFromFormat('Y-m-d', $start_date)->addYear(2)->subDay(); //起算日から2年後マイナス1日が期限日
//				$paid_vacations->remaining_days = $paid_vacations->getRemainingDays($base_date, $start_date); //今日時点での有給残日数を取得し、格納
//				$paid_vacations->save();
//
//				$j++;
//			}

			\Session::flash('flashMessage', 'ユーザ情報を保存しました');
			//return redirect()->back(); //編集ページに留まる時はこっち。
			return redirect('/user'); //一覧ページに戻るときはこっち。
		}

		return view('auth.register');
	}

}
