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

	public function index()
	{

		$num = 10; //1ページに表示するレコード数

		if (Input::exists('reset')) { //リセットボタンが押された場合
			$devices = Device::orderBy('bought_at', 'desc')->paginate($num);
			$category = 0;
			$os = '';
			$name = '';
			$after = '';
			$before = '';
			$user_id = null;
			$disposal = null;
		} else {

			//検索ボタンが押された場合、検索欄の値を取得
			$category = Input::get('category');
			$os = Input::get('os');
			$name = Input::get('name');
			$after = (Input::get('after')) ? User::getStdDate(Input::get('after')) : '';
			$before = (Input::get('before')) ? User::getStdDate(Input::get('before')) : '';
			$user_id = Input::get('user_id');
			$disposal = Input::get('disposal');

			//queryインスタンスを生成
			$query = Device::query();

			//検索欄で文字が渡されればクエリに条件を追加
			if (!empty($category)) {
				$query->where('category', '=', $category);
			}
			if (!empty($os)) {
				$query->where('os', '=', $os);
			}
			if (!empty($name)) {
				$query->where('name', 'like', '%' . $name . '%');
			}
			if (!empty($after)) { //複雑な条件を指定する
				$query->where('bought_at', '>=', $after);
			}
			if (!empty($before)) { //複雑な条件を指定する
				$query->where('bought_at', '<=', $before);
			}
			if (!empty($user_id)) {
				$query->where('user_id', '=', $user_id);
			}
			if ($disposal == 1) {
				$query->where('status', '=', 99);
			}
			//指定した条件に当てはまるレコードをpaginate
			$devices = $query->paginate($num);
		}

		$users = User::all()->pluck('last_name', 'id');
		return view('device.index', compact('devices', 'users'))
						->with('category', $category)
						->with('os', $os)
						->with('name', $name)
						->with('after', $after)
						->with('before', $before)
						->with('user_id', $user_id)
						->with('disposal', $disposal);
	}

	const MESSAGES = [
		'name.max' => '機器名は :max 文字以内で入力してください',
		'serial_id.required' => 'シリアルIDは必須項目です',
		'core.max' => 'コア数は :max 文字以内で入力してください',
		'core.integer' => 'コア数は数値で入力してください',
		'memory.max' => 'メモリは :max 文字以内で入力してください',
		'memory.integer' => 'メモリは数値で入力してください',
		'size.max' => 'サイズは :max 文字以内で入力してください',
	];

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
