<?php

namespace Techup\SimpleTokenApi\Enums;


class RequestTypeEnum extends TypedEnumHelper {
	/** @return $this */
	public static function POST() { return self::_create('POST'); }
	/** @return $this */
	public static function GET() { return self::_create('GET'); }
}