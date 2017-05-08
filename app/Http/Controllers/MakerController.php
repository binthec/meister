<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Carbon\Carbon;
use App\Maker;

class MakerController extends Controller
{

	const PAGINATION = 20;

	/**
	 * 一覧と検索
	 * 
	 * @param Request $request
	 * @return 一覧画面
	 */
	public function index(Request $request)
	{
		$makers = Maker::paginate(self::PAGINATION);
		return view('maker.index', compact('makers'));
	}

	/**
	 * 登録
	 * 
	 * @return 登録画面
	 */
	public function create()
	{
		$maker = new Maker;
		return view('maker.edit', compact('maker'));
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
		]);
		if ($validator->fails()) {
			return redirect()
							->back()
							->withErrors($validator)
							->withInput();
		}

		DB::beginTransaction();

		try {
			$maker = new Maker;
			$maker->name = $request->name;
			$maker->save();
			DB::commit();

			return redirect('/maker')->with('flashMsg', '新規登録が完了しました。');
		} catch (\Exception $e) {
			DB::rollback();
			Log::error($e->getMessage());
			return redirect('/maker')->with('flashErrMsg', '新規作成に失敗しました。時間をおいて再度お試しください。');
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
		$maker = Maker::find($id);
		return view('maker.edit', compact('maker'));
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
		]);
		if ($validator->fails()) {
			return redirect()
							->back()
							->withErrors($validator)
							->withInput();
		}

		DB::beginTransaction();

		try {
			$maker = Maker::findOrFail($id);
			$maker->name = $request->name;
			$maker->deleted_at = (isset($request->deleted)) ? Carbon::now() : null;
			$maker->save();
			DB::commit();

			return redirect('/maker')->with('flashMsg', 'デバイスの編集が完了しました');
		} catch (\Exception $e) {
			DB::rollback();
			Log::error($e->getMessage());
			return redirect('/maker')->with('flashErrMsg', 'デバイスの編集に失敗しました。時間をおいて再度お試しください。');
		}
	}

}
