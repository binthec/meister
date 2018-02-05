<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    protected $table = 'informations';

    /**
     * お知らせのステータス
     */
    const STATUS_OPEN = 1;
    const STATUS_CLOSE = 0;

    public static $status = [
        self::STATUS_OPEN => '公開',
        self::STATUS_CLOSE => '非公開',
    ];

    /**
     * 公開ステータスのものを、降順で抽出するローカルスコープ
     *
     * @param $query
     * @return mixed
     */
    public function scopeOpen($query)
    {
        return $query->where('status', self::STATUS_OPEN)->orderBy('updated_at', 'desc');
    }
}
