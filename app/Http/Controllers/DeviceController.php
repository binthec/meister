<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;
use Validator;
use App\User;
use App\Device;

class DeviceController extends Controller
{

	const PAGINATION = 10;

	public function index(Request $request)
	{
		$query = Device::getSearchQuery($request->input());
		$devices = $query->paginate(self::PAGINATION);
		return view('device.index')
						->with('devices', $devices)
						->with($request->input());
	}

	public function add(Request $request)
	{

		if ($request->isMethod('post')) {

			$validator = Validator::make($request->all(), [
						'name' => 'max:200',
						'serial_id' => 'required',
						'core' => 'max:50|integer',
						'memory' => 'max:50|integer',
						'size' => 'max:50',
							], self::MESSAGES);

			if ($validator->fails()) {
				return redirect()
								->back()
								->withErrors($validator)
								->withInput();
			}

			$device = new Device;
			$device->category = $request->category;
			$device->os = $request->os;
			$device->name = $request->name;
			$device->serial_id = $request->serial_id;
			$device->bought_at = User::getStdDate($request->bought_at);
			$device->user_id = ($request->user_id != '') ? $request->user_id : null;
			$device->status = ($request->user_id != '') ? 1 : 2; //1=使用中、2=使用されていません
			$device->core = $request->core;
			$device->memory = $request->memory;
			$device->size = $request->size;
			$device->save();

			\Session::flash('flashMsg', 'デバイスの新規登録が完了しました');
			return redirect('/device');
		}

		$users = User::all()->pluck('last_name', 'id');
		return view('device.add', compact('users'));
	}

	public function edit(Request $request, $id)
	{
		$device = Device::find($id);

		if ($request->isMethod('post')) {

			//ステータスを先に判定
			$status = 1;
			if (isset($request->disposal)) {
				$status = 99;
			} elseif (!$request->user_id) {
				$status = 2;
			}

			$device->category = $request->category;
			$device->os = $request->os;
			$device->name = $request->name;
			$device->serial_id = $request->serial_id;
			$device->bought_at = User::getStdDate($request->bought_at);
			$device->user_id = ($request->user_id != '') ? $request->user_id : null;
			$device->status = $status;
			$device->core = $request->core;
			$device->memory = $request->memory;
			$device->size = $request->size;
			$device->save();

			\Session::flash('flashMsg', 'デバイスの編集が完了しました');
			return redirect('/device');
		}

		$users = User::all()->pluck('last_name', 'id');
		return view('device.edit', compact('device', 'users'));
	}

}
