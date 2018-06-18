<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Validator;
use Illuminate\Support\Facades\Route;
use App\User;
use App\PaidVacation;
use App\UsedDays;
use Session;
use Carbon\Carbon;

class UserController extends Controller
{

    protected $today;

    const PAGINATION = 10;

    public function __construct()
    {
        $this->today = Carbon::now()->toDateString(); //今日の日付を取得;
    }

    /**
     * 一覧と検索
     *
     * @return ユーザ一覧画面
     */
    public function index(Request $request)
    {
        $query = User::getSearchQuery($request->input());
        $users = $query->paginate(self::PAGINATION);
        return view('user.index', ['users' => $users, 'today' => $this->today]);
    }

    /**
     * ユーザ詳細
     *
     * @param str $id
     * @return ユーザ詳細画面
     */
    public function show($id)
    {
        $user = User::find($id);
        $validPaidVacations = $user->getValidPaidVacation(User::getTodayDate());
        $usedDays = $user->usedDays()->orderBy('from', 'descs')->paginate(self::PAGINATION); //登録済み有給を取得
        return view('user.show', compact('user', 'validPaidVacations', 'usedDays'));
    }

    /**
     * ユーザ情報編集画面表示
     *
     * @param str $id
     * @return ユーザ情報編集画面
     */
    public function edit($id)
    {
        $user = User::find($id);
        return view('user.edit', compact('user'));
    }

    /**
     * 編集実行
     *
     * @param Request $request
     * @param str $id
     * @return ユーザ詳細画面へ戻る
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->role !== User::ADMIN && Auth::user()->id !== (int)$id) {

            return redirect()
                ->back()
                ->withErrors(['permission' => '自身以外のユーザ情報変更することはできません']);
        }

        $validator = Validator::make($request->all(), [
            'last_name' => 'required|max:255',
            'first_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
        ]);
        if ($validator->fails()) {

            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);
            $user->last_name = $request->last_name;
            $user->first_name = $request->first_name;
            $user->email = $request->email;
            $user->status = ($request->retire_flg == 1) ? User::RETIRED : User::ACTIVE;
            $user->type_of_employment = $request->type_of_employment;
            $user->department = $request->department;
            $user->role = ($request->role) ? $request->role : $user->role;
            $user->memo = $request->memo;
            $user->save();
            DB::commit();

            return redirect()->action('UserController@show', $id)->with('flashMsg', 'ユーザ情報の変更を完了しました。');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return redirect()->action('UserController@show', $id)->with('flashErrMsg', 'ユーザ情報の変更に失敗しました。');
        }
    }

    /**
     * パスワード変更画面
     *
     * @param Request $request
     * @param str $id
     * @return パスワード変更画面
     */
    public function passwordEdit(Request $request, $id)
    {
        $user = User::find($id);
        return view('user.password_edit', compact('user'));
    }

    /**
     * パスワード変更実行
     *
     * @param Request $request
     * @param str $id
     * @return ユーザ詳細画面に戻る
     */
    public function passwordUpdate(Request $request, $id)
    {

        if (Auth::user()->role !== User::ADMIN && Auth::user()->id !== (int)$id) {

            return redirect()
                ->back()
                ->withErrors(['permission' => '自身以外のユーザ情報変更することはできません']);
        }

        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8',
            'password_confirmation' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);
            $user->password = bcrypt($request->password);
            $user->save();
            DB::commit();

            return redirect()->action('UserController@show', $id)->with('flashMsg', 'パスワードの変更を完了しました');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return redirect()->action('UserController@show', $id)->with('flashErrMsg', 'パスワードの変更に失敗しました。');
        }
    }

    /**
     * 入社日変更
     *
     * @param str $id
     * @return 入社日変更画面
     */
    public function dateEdit($id)
    {
        $user = User::find($id);
        return view('user.date_edit', compact('user'));
    }

    /**
     * 入社日変更実行
     *
     * @param Request $request
     * @param str $id
     * @return ユーザ詳細画面に戻る
     */
    public function dateUpdate(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $user = User::findOrFail($id);
            $user->date_of_entering = User::getStdDate($request->date_of_entering); //入社日
            $user->base_date = User::getStdDate($request->base_date); //起算日
            $user->save();

            //1.編集するユーザIDを持つ有給レコードを物理削除
            PaidVacation::where('user_id', $id)->delete();

            //2.有給を最初から再計算して、新規に、現在までの有給レコード生成
            $user->setOriginalPaidVacations();

            //3.既に登録されている有給消化登録の日数を有給レコードから減算
            $user->recalcRemainingDays();

            DB::commit();
            return redirect()->action('UserController@show', $id)->with('flashMsg', '入社日の変更を完了しました');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return redirect()->action('UserController@show', $id)->with('flashErrMsg', '入社日の変更に失敗しました。');
        }
    }

    /**
     *
     * @param str $userId
     * @return type
     */
    public function reset($id = null)
    {
        $user = User::find($id);

        $paid_vacations = PaidVacation::where('user_id', $id)->get(); //編集するユーザIDを持つ有給レコードを取得
        foreach ($paid_vacations as $paid_vacation) { //レコードが存在する場合、一旦物理削除
            $paid_vacation->delete();
        }

        //有給の再計算、データ生成後にレコード保存
        $user->setOriginalPaidVacations();

        $usedDays = UsedDays::where('user_id', $id)->delete();

        \Session::flash('flashMsg', 'リセット完了');
        return redirect()->back(); //再計算後はdashboardに戻る
    }

}
