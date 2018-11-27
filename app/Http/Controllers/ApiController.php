<?php

namespace App\Http\Controllers;

use App\Attendance;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ApiController extends Controller
{
    public function timecard(Request $request)
    {
        if (trim($request->input('token')) !== '59b8N117i2fIm8FT0xSTBHeV') {
            throw new BadRequestHttpException();
        }

        try {

            $user = User::where('slack_user_id', trim($request->input('user_id')))->first();

            if (!$user) {
                return response("ユーザが見つかりませんでした。下記にアクセスして、あなたの SlackUserID ( ".$request->input('user_id')." ) を登録してください。\nhttps://meister.moremost.biz", 200);
            }

            $status = $this->detectStatus($request->input('text'));

            if ($status === Attendance::STATUS_UNKNOWN) {
                return response('出勤・退勤の判別が出来ませんでした。', 200);
            }

            $attendance = new Attendance();
            $attendance->user_id = $user->id;
            $attendance->slack_text = $request->input('text');
            $attendance->status = $status;
            $attendance->raw_data = json_encode($request->all());
            $attendance->save();

            $statusLabel = $attendance->getStatusLabel();

            $response = [
                'response_type' => 'in_channel',
                'text' => $user->last_name . ' ' . $user->first_name . 'さんが'. $statusLabel .'しました。 ['.date('H:i').' '.$statusLabel.']',
            ];

            return response()->json($response, 200);

        } catch (\Exception $e) {

            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
        }
    }

    private function detectStatus($text)
    {
        $wordsOfStartWorking = [
            'おはよう',
            'はじめます',
            '始めます',
            '出社しました',
            '出勤しました',
            'Start',
            'start'
        ];

        $wordsOfEndWorking = [
            'お疲れ様',
            'おわります',
            '終わります',
            'おえます',
            '終えます',
            '退社します',
            '退勤します',
            'End',
            'end'
        ];

        if (count(array_filter($wordsOfStartWorking, function ($keyword) use ($text) {
            return mb_strpos($text, $keyword) !== false;
        })) > 0) {
            return Attendance::STATUS_STRAT_WORKING;
        }
        if (count(array_filter($wordsOfEndWorking, function ($keyword) use ($text) {
                return mb_strpos($text, $keyword) !== false;
            })) > 0) {
            return Attendance::STATUS_END_WORKING;
        }

        return Attendance::STATUS_UNKNOWN;
    }
}
