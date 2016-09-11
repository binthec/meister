<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use App\UsedDays;

class HomeController extends Controller
{

	public function dashboard()
	{
		$validPaidVacations = Auth::user()->getValidPaidVacation();
		return view('home.dashboard', compact('validPaidVacations'));
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
