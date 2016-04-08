<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;

class HomeController extends Controller {

	public function dashboard() {
		//$items = Item::all();
		//dd($items);
		$user = Auth::user();
		$today = Carbon::now();
//		dd($today);
//		dd($user);
		//return view('home.dashboard', ['items' => $items]);
//		dd($user);
		return view('home.dashboard', ['user' => $user, 'today' => $today]);
	}

	public function create() {
		//
	}

	public function store(Request $request) {
		//
	}

	public function show($id) {
		//
	}

	public function edit($id) {
		//
	}

	public function update(Request $request, $id) {
		//
	}

	public function destroy($id) {
		//
	}

}
