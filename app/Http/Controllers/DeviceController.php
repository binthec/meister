<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Input;
use Validator;
use Carbon\Carbon;
use App\User;
use App\Device;

class DeviceController extends Controller
{

	const PAGINATION = 10;

	/**
	 * 一覧と検索
	 * 
	 * @param Request $request
	 * @return 一覧画面
	 */
	public function index(Request $request)
	{
		$query = Device::getSearchQuery($request->input());
		$devices = $query->paginate(self::PAGINATION);
		return view('device.index')
						->with('devices', $devices)
						->with($request->input());
	}

	/**
	 * 登録
	 * 
	 * @return 登録画面
	 */
	public function create()
	{
		$device = new Device();
		$device->bought_at = Carbon::now();
		return view('device.edit', compact('device'));
	}

	/**
	 * 登録実行
	 * 
	 * @param Request $request
	 * @return 一覧へ戻る
	 */
	public function store(Request $request)
	{
		$validator = Validator::make($request->all(), [
					'name' => 'required|max:200',
					'serial_id' => 'required',
					'core' => 'max:50|integer',
					'memory' => 'max:50|integer',
					'size' => 'max:50',
		]);
		if ($validator->fails()) {
			return redirect()
							->back()
							->withErrors($validator)
							->withInput();
		}

		DB::beginTransaction();

		try {
			$device = new Device;
			$device->category = $request->category;
			$device->os = ($device->category === Device::DISPLAY || $device->category === Device::OTHER) ? null : $request->os;
			$device->name = $request->name;
			$device->serial_id = $request->serial_id;
			$device->bought_at = User::getStdDate($request->bought_at);
			$device->user_id = ($request->user_id != '') ? $request->user_id : null;
			$device->status = ($request->user_id != '') ? Device::DEVICE_USED : Device::DEVICE_UNUSED;
			$device->core = ($device->category === Device::DISPLAY || $device->category === Device::OTHER) ? null : $request->core;
			$device->memory = ($device->category === Device::DISPLAY || $device->category === Device::OTHER) ? null : $request->memory;
			$device->capacity = ($device->category === Device::DISPLAY || $device->category === Device::OTHER) ? null : $request->capacity;
			$device->size = $request->size;
			$device->save();
			DB::commit();

			return redirect('/device')->with('flashMsg', 'デバイスの新規登録が完了しました。');
		} catch (\Exception $e) {
			DB::rollback();
			Log::error($e->getMessage());
			return redirect('/device')->with('flashErrMsg', 'デバイスの新規作成に失敗しました。時間をおいて再度お試しください。');
		}
	}

	/**
	 * 編集
	 * 
	 * @param str $id
	 * @return 編集画面
	 */
	public function edit($id)
	{
		$device = Device::find($id);
		return view('device.edit', compact('device'));
	}

	/**
	 * 編集実行
	 * 
	 * @param Request $request
	 * @param str $id
	 * @return 一覧へ戻る
	 */
	public function update(Request $request, $id)
	{
		$validator = Validator::make($request->all(), [
					'name' => 'required|max:200',
					'serial_id' => 'required',
					'core' => 'max:50|integer',
					'memory' => 'max:50|integer',
					'size' => 'max:50',
		]);
		if ($validator->fails()) {
			return redirect()
							->back()
							->withErrors($validator)
							->withInput();
		}

		DB::beginTransaction();

		try {
			//ステータスを先に判定
			$status = 1;
			if (isset($request->disposal)) {
				$status = 99;
			} elseif (!$request->user_id) {
				$status = 2;
			}

			$device = Device::find($id);
			$device->category = $request->category;
			$device->os = ($device->category === Device::DISPLAY || $device->category === Device::OTHER) ? null : $request->os;
			$device->name = $request->name;
			$device->serial_id = $request->serial_id;
			$device->bought_at = User::getStdDate($request->bought_at);
			$device->user_id = ($request->user_id != '') ? $request->user_id : null;
			$device->status = $status;
			$device->core = ($device->category === Device::DISPLAY || $device->category === Device::OTHER) ? null : $request->core;
			$device->memory = ($device->category === Device::DISPLAY || $device->category === Device::OTHER) ? null : $request->memory;
			$device->capacity = ($device->category === Device::DISPLAY || $device->category === Device::OTHER) ? null : $request->capacity;
			$device->size = $request->size;
			$device->save();
			DB::commit();

			return redirect('/device')->with('flashMsg', 'デバイスの編集が完了しました');
		} catch (\Exception $e) {
			DB::rollback();
			Log::error($e->getMessage());
			return redirect('/device')->with('flashErrMsg', 'デバイスの編集に失敗しました。時間をおいて再度お試しください。');
		}
	}

}
