<?php

namespace Techup\SimpleTokenApi\Enums;


class KindEnum extends TypedEnumHelper {
	/** @return $this */
	public static function user_to_user() { return self::_create('user_to_user'); }
	/** @return $this */
	public static function company_to_user() { return self::_create('company_to_user'); }
	/** @return $this */
	public static function user_to_company() { return self::_create('user_to_company'); }
}