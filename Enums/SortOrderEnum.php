<?php

namespace Techup\SimpleTokenApi\Enums;


class SortOrderEnum extends TypedEnumHelper {
	/** @return $this */
	public static function ASC() { return self::_create('asc'); }
	/** @return $this */
	public static function DESC() { return self::_create('desc'); }
}