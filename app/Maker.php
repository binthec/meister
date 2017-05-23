<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;

class Maker extends Model
{

	use SoftDeletes;

	/**
	 * メーカーはライセンスをたくさん持ってる
	 * 
	 * @return リレーション
	 */
	public function licenses()
	{
		return $this->hasMany(License::class);
	}

	/**
	 * メーカー名一覧を配列にして返すメソッド
	 * 
	 * @return array
	 */
	public static function getNames()
	{
		return (self::get()) ? self::get()->pluck('name', 'id')->toArray() : ['先にメーカーを登録してください'];
	}

}
