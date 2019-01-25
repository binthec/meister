<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * デバイス分類
     */
    const NOTE_PC = '1';
    const DESKTOP_PC = '2';
    const TABLET = '20';
    const DISPLAY = '40';
    const OTHER = '100';
    const CATEGORY_OTHERS = '200';

    public static $deviceCategories = [
        self::NOTE_PC => 'ノートパソコン',
        self::DESKTOP_PC => 'デスクトップパソコン',
        self::TABLET => 'タブレット',
        self::DISPLAY => 'ディスプレイ',
        self::OTHER => '周辺機器',
        self::CATEGORY_OTHERS => 'その他',
    ];
    public static $deviceIcon = [
        self::NOTE_PC => 'fa-laptop',
        self::DESKTOP_PC => 'fa-desktop',
        self::TABLET => 'fa-tablet',
        self::DISPLAY => 'fa-television',
        self::OTHER => 'fa-usb',
        self::CATEGORY_OTHERS => 'fa-cube',
    ];

    /**
     * OS種別
     */
    const MAC = '1';
    const WIN = '2';
    const LINUX = '3';
    const OS_OTHERS = '4';

    public static $osLabels = [
        self::MAC => 'Mac',
        self::WIN => 'Windows',
        self::LINUX => 'Linux',
        self::OS_OTHERS => 'その他',
    ];

    /**
     * デバイスステータス
     */
    const DEVICE_USED = '1';
    const DEVICE_UNUSED = '2';
    const DISPOSAL = '99';

    public static $deviceStatus = [
        self::DEVICE_USED => '使用中',
        self::DEVICE_UNUSED => '使用されていません',
        self::DISPOSAL => '廃棄済',
    ];

    /**
     * デバイスの状態
     */
    const CONDITION_NEW = '1';
    const CONDITION_USED = '2';
    const CONDITION_HAND_OVER = '3';
    const CONDITION_OTHERS = '4';

    public static $conditionLabels = [
        self::CONDITION_NEW => '新品',
        self::CONDITION_USED => '中古',
        self::CONDITION_HAND_OVER => '受け入れ',
        self::CONDITION_OTHERS => 'その他',
    ];

    /**
     * バリデーションルールの配列を返す
     *
     * @return array
     */
    public static function getValidationRules()
    {

        return [
            'name' => 'required|max:200',
            'serial_id' => 'required',
            'core' => 'max:50|integer',
            'memory' => 'max:50|integer',
            'size' => 'max:50',
        ];

    }

    /**
     * 検索用のクエリビルダを返すメソッド
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getSearchQuery(array $data)
    {
        //queryインスタンスを生成
        $query = Device::query();

        //購入日の新しい順に並べる
        $query->orderBy('bought_at', 'desc');

        //検索欄で文字が渡されればクエリに条件を追加
        if (!empty($data['category'])) {
            $query->where('category', $data['category']);
        }
        if (!empty($data['os'])) {
            $query->where('os', $data['os']);
        }
        if (!empty($data['name'])) {
            $query->where('name', 'like', '%' . $data['name'] . '%');
        }
        if (!empty($data['after'])) {
            $query->where('bought_at', '>=', User::getStdDate($data['after']));
        }
        if (!empty($data['before'])) {
            $query->where('bought_at', '<=', User::getStdDate($data['before']));
        }
        if (!empty($data['user_name'])) {
            $users = User::where(function ($query) use ($data) {
                $query->orWhere('last_name', 'like', $data['user_name'])
                    ->orWhere('first_name', 'like', $data['user_name']);
            })->get();
            $query->whereIn('user_id', $users->pluck('id'));
        }
        if (!isset($data['searchInactive'])) {
            $query->where('status', '<>', self::DISPOSAL);
        }

        return $query;
    }

}
