<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\UsedDays;

class HomeController extends Controller
{

	public function dashboard()
	{
		$validPaidVacations = Auth::user()->getValidPaidVacation();
		$usedDays = Auth::user()->usedDays()->orderBy('from', 'descs')->paginate(UsedDays::PAGE_NUM); //登録済み有給を取得
		return view('home.dashboard', compact('validPaidVacations', 'usedDays'));
	}

	public function store(Request $request)
	{
		//
	}

	public function show($id)
	{
		//
	}

	public function edit($id)
	{
		//
	}

	public function update(Request $request, $id)
	{
		//
	}

	public function destroy($id)
	{
		//
	}

}
