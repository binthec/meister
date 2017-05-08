<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\License;

class LicenseController extends Controller
{

	/**
	 * 一覧と検索
	 * 
	 * @param Request $request
	 * @return 一覧画面
	 */
	public function index()
	{
		$licenses = License::all();
//		$makers = Maker::getSearchQuery($request->input())->paginate(self::PAGINATION);
		return view('license.index', compact('licenses'));
	}

	/**
	 * 登録
	 * 
	 * @return 登録画面
	 */
	public function create()
	{
		$license = new License();
		$license->bought_at = Carbon::now();
		return view('license.edit', compact('license'));
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
			$license = new License;
			$license->category = $request->category;
			$license->os = ($license->category === License::DISPLAY) ? null : $request->os;
			$license->name = $request->name;
			$license->serial_id = $request->serial_id;
			$license->bought_at = User::getStdDate($request->bought_at);
			$license->user_id = ($request->user_id != '') ? $request->user_id : null;
			$license->status = ($request->user_id != '') ? License::DEVICE_USED : DEVICE_USED::DEVICE_UNUSED;
			$license->core = ($license->category === License::DISPLAY) ? null : $request->core;
			$license->memory = ($license->category === License::DISPLAY) ? null : $request->memory;
			$license->capacity = ($license->category === License::DISPLAY) ? null : $request->capacity;
			$license->size = $request->size;
			$license->save();
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
		$license = License::find($id);
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

			$license = License::find($id);
			$license->category = $request->category;
			$license->os = ($license->category === License::DISPLAY) ? null : $request->os;
			$license->name = $request->name;
			$license->serial_id = $request->serial_id;
			$license->bought_at = User::getStdDate($request->bought_at);
			$license->user_id = ($request->user_id != '') ? $request->user_id : null;
			$license->status = $status;
			$license->core = ($license->category === License::DISPLAY) ? null : $request->core;
			$license->memory = ($license->category === License::DISPLAY) ? null : $request->memory;
			$license->capacity = ($license->category === License::DISPLAY) ? null : $request->capacity;
			$license->size = $request->size;
			$license->save();
			DB::commit();

			return redirect('/device')->with('flashMsg', 'デバイスの編集が完了しました');
		} catch (\Exception $e) {
			DB::rollback();
			Log::error($e->getMessage());
			return redirect('/device')->with('flashErrMsg', 'デバイスの編集に失敗しました。時間をおいて再度お試しください。');
		}
	}

}
