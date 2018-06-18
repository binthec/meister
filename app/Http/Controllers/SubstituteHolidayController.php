<?php

namespace App\Http\Controllers;

use App\SubstituteHoliday;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Validator;

class SubstituteHolidayController extends Controller
{

    const PAGINATION = 10;

    protected $rules = [
        'user_id' => 'required',
        'workday' => 'required',
        'holiday' => 'required',
        'memo' => 'max:2000',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = User::find(Auth::user()->id);

        $substituteHolidays = $user->substituteHolidays()->paginate(self::PAGINATION); //登録済み有給を取得

        return view('substitute_holiday.index', compact('substituteHolidays'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::find(Auth::user()->id);
        $substituteHolidays = $user->substituteHolidays()->paginate(self::PAGINATION); //登録済み有給を取得

        return view('substitute_holiday.create', compact('substituteHolidays'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();

        try {
            $substituteHoliday  = new SubstituteHoliday();

            $substituteHoliday->user_id = (int)$request->get('user_id');
            $substituteHoliday->workday = User::getStdDate($request->get('workday'));
            $substituteHoliday->holiday = User::getStdDate($request->get('holiday'));
            $substituteHoliday->memo = $request->get('memo');
            $substituteHoliday->save();

            DB::commit();

            return redirect('substitute_holiday/create');

        } catch (\Exception $e) {

            DB::rollback();
            Log::error($e->getMessage());
        }
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
        /**
        $useRequest = UsedDays::find($id);
        $usedDays = Auth::user()->usedDays()->orderBy('from', 'descs')->paginate(self::PAGINATION); //登録済み有給を取得
        $validPaidVacations = Auth::user()->getValidPaidVacation(User::getTodayDate());
        return view('vacation.edit', compact('useRequest', 'usedDays', 'validPaidVacations'));
         **/
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
        $substituteHoliday = SubstituteHoliday::find($id);
        $substituteHoliday->delete();

        return redirect()->back()->with('flashMsg', '出勤振替を削除しました');
    }
}
