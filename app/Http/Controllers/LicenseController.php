<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\License;

class LicenseController extends Controller
{

	/**
	 * １ページに表示する数
	 */
	const PAGINATION = 20;

	/**
	 * 一覧と検索
	 * 
	 * @param Request $request
	 * @return 一覧画面
	 */
	public function index()
	{
		$licenses = License::paginate(self::PAGINATION);
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
		$validator = Validator::make($request->all(), License::getValidationRule());
		if ($validator->fails()) {
			return redirect()
							->back()
							->withErrors($validator)
							->withInput();
		}

		DB::beginTransaction();

		try {

			$license = new License;
			$license->saveAll($request);
			DB::commit();

			return redirect('/license')->with('flashMsg', '新規登録が完了しました。');
		} catch (\Exception $e) {
			DB::rollback();
			Log::error($e->getMessage());
			return redirect('/license')->with('flashErrMsg', '新規作成に失敗しました。時間をおいて再度お試しください。');
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
		return view('license.edit', compact('license'));
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
		$validator = Validator::make($request->all(), License::getValidationRule());
		if ($validator->fails()) {
			return redirect()
							->back()
							->withErrors($validator)
							->withInput();
		}

		DB::beginTransaction();

		try {

			$license = License::find($id);
			$license->saveAll($request);
			DB::commit();

			return redirect('/license')->with('flashMsg', '編集が完了しました');
		} catch (\Exception $e) {
			DB::rollback();
			Log::error($e->getMessage());
			return redirect('/license')->with('flashErrMsg', '編集に失敗しました。時間をおいて再度お試しください。');
		}
	}

}
