<?php

namespace App\Http\Controllers;

use App\Information;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\UsedDays;
use App\User;

class HomeController extends Controller
{

    /**
     * お知らせのダッシュボードでの表示件数
     */
    const INFO_NUM = 5;

    /**
     * ダッシュボード
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function dashboard()
    {
        $validPaidVacations = Auth::user()->getValidPaidVacation(User::getTodayDate());
        $usedDays = Auth::user()->usedDays()->orderBy('from', 'descs')->paginate(UsedDays::PAGE_NUM); //登録済み有給を取得
        $informations = Information::open()->take(self::INFO_NUM)->get();
        return view('home.dashboard', compact('validPaidVacations', 'usedDays', 'informations'));
    }

    /**
     * お知らせ詳細
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function info($id)
    {
        $info = Information::findOrFail($id);
        return view('home.info', compact('info'));
    }

}
