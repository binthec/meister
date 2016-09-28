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

//	const DISPLAY = '40';

	public static $deviceCategories = [
		self::NOTE_PC => 'ノートパソコン',
		self::DESKTOP_PC => 'デスクトップパソコン',
		self::TABLET => 'タブレット',
//		self::DISPLAY => 'ディスプレイ',
	];

	/**
	 * OS種別
	 */
	const MAC = '1';
	const WIN = '2';
	const LINUX = '3';

	public static $osLabels = [
		self::MAC => 'Mac',
		self::WIN => 'Windows',
		self::LINUX => 'Linux',
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

}
