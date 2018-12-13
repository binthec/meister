<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\User;

class Attendance extends Model
{
    const STATUS_STRAT_WORKING = 10;
    const STATUS_END_WORKING = 90;
    const STATUS_UNKNOWN = 99;

    const PER_PAGE = 15;

    protected $fillable = ['id', 'user_id', 'slack_text', 'status', 'raw_data', 'create_at', 'update_at'];

    protected $statusLabels = [
        self::STATUS_STRAT_WORKING => '出勤',
        self::STATUS_END_WORKING => '退勤',
        self::STATUS_UNKNOWN => '不明'
    ];

    public function getStatusLabel()
    {
        return $this->statusLabels[$this->status];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }




    /**
     * タイムカード一覧画面で、検索用のqueryを吐き出すメソッド
     *
     * @param array $data
     * @return $query
     */
    static function getSearchQuery(array $data)
    {

        $query = Attendance::query();

        /* 管理者以外は自分のデータのみに限定 */
        if(Auth::user()->role !== User::ADMIN) {
            $query->where('user_id', Auth::user()->id);
        }

        if (!empty($data['user_name'])) {

            $users = User::where(function($query) use ($data) {
                $query->orWhere('last_name', 'like', $data['user_name'])
                    ->orWhere('first_name', 'like', $data['user_name']);
            })->get();
            $query->whereIn('user_id', $users->pluck('id'));
        }
        /* 出勤・退勤・有給・欠勤の判定 */
        if (!empty($data['status'])) {
            /* 連想配列を配列に変換 */
            $query->whereIn('status', array_values( $data['status'] ));
        }
        /* 時分秒を追加し、検索結果に指定した日付を含める */
        if (!empty($data['after'])) {
            $query->where('created_at', '>=', User::getStdDate($data['after']) . ' 0:00:00');
        }
        if (!empty($data['before'])) {
            $query->where('created_at', '<=', User::getStdDate($data['before']) . ' 23:59:99');
        }

        return $query;
    }

}
