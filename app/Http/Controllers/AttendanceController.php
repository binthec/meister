<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AttendanceController extends Controller
{

    const PAGINATION = 10;


    /**
     * 一覧と検索
     *
     * @return タイムカード一覧画面
     */

    public function index(Request $request)
    {

        $query = Attendance::getSearchQuery($request->input());
        $attendances = $query->paginate(self::PAGINATION);
        return view('attendance.index', ['attendances' => $attendances]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * リクエストの条件で検索した後、結果をJSON形式で返す
     *
     * @return JSON形式のデータ
     */

    public function getJSON(Request $request){
        $query = Attendance::getSearchQuery($request->input());
        return json_encode($query->get());
    }

    /**
     * CSVファイルを生成し、ファイルパスへのレスポンスを返す
     *
     * @return CSVファイルへのレスポンス
     */

    public function downloadCsv()
    {
        $fileUrl = Attendance::exportAttendancesToCsv();

        $date = Carbon::today()->format('Ymd');
        return response()->download($fileUrl, 'sample' . $date . '.csv')
            ->deleteFileAfterSend(true);
    }

}
