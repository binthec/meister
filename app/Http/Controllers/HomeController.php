<?php

namespace App\Http\Controllers;

use App\SubstituteHoliday;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\UsedDays;
use App\User;

class HomeController extends Controller
{

	public function dashboard()
	{

		$validPaidVacations = Auth::user()->getValidPaidVacation(User::getTodayDate());
		$usedDays = Auth::user()->usedDays()->orderBy('from', 'descs')->paginate(UsedDays::PAGE_NUM); //登録済み有給を取得
        $substituteHolidays = Auth::user()->substituteHolidays()->paginate(SubstituteHoliday::PAGE_NUM); //登録済み有給を取得

		return view('home.dashboard', compact('validPaidVacations', 'usedDays', 'substituteHolidays'));
	}

}
