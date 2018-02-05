<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Validator;
use App\Information;

class InformationController extends Controller
{

    const PAGINATION = 20;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $informations = Information::paginate(self::PAGINATION);
        return view('info.index', compact('informations'));
    }

    /**
     * 登録
     *
     * @return 登録画面
     */
    public function create()
    {
        $info = new Information();
        return view('info.edit', compact('info'));
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
            'title' => 'required|max:250',
            'body' => 'max:250',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();

        try {
            $info = new Information;
            $info->title = $request->title;
            $info->body = $request->body;
            $info->status = $request->status;
            $info->save();
            DB::commit();

            return redirect('/info')->with('flashMsg', 'お知らせの新規登録が完了しました。');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return redirect('/info')->with('flashErrMsg', 'お知らせの新規作成に失敗しました。時間をおいて再度お試しください。');
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
        $info = Information::find($id);
        return view('info.edit', compact('info'));
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
            'title' => 'required|max:250',
            'body' => 'max:2000',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        DB::beginTransaction();

        try {
            $info = Information::findOrFail($id);
            $info->title = $request->title;
            $info->body = $request->body;
            $info->status = $request->status;
            $info->save();
            DB::commit();

            return redirect('/info')->with('flashMsg', 'お知らせの編集が完了しました');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return redirect('/info')->with('flashErrMsg', 'お知らせの編集に失敗しました。時間をおいて再度お試しください。');
        }
    }

}