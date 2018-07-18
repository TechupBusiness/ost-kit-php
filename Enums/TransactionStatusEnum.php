<?php

namespace Techup\SimpleTokenApi\Enums;


class TransactionStatusEnum extends TypedEnumHelper {
	/** @return $this */
	public static function processing() { return self::_create('processing'); }
	/** @return $this */
	public static function failed() { return self::_create('failed'); }
	/** @return $this */
	public static function complete() { return self::_create('complete'); }
}