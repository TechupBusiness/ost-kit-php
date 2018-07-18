<?php

namespace Techup\SimpleTokenApi\Models;

use Techup\SimpleTokenApi\Enums\KindEnum;

class ActionModel {
	/* @var integer $id     identifier for the created action */
	public $id;

	/* @var string $name	(mandatory) unique name of the action */
	public $name;

	/* @var KindEnum $kind	    an action can be one of three kinds: "user_to_user", "company_to_user", or "user_to_company" */
	public $kind;

	/* @var string $currency_type	(mandatory) type of currency the action amount is specified in. Possible values are "USD" (fixed) or "BT" (floating). When an action is set in fiat the equivalent amount of branded tokens are calculated on-chain over a price oracle. For OST KIT⍺ price points are calculated by and taken from coinmarketcap.com and published to the contract by OST.com.  */
	public $currency;

	/* @var bool $arbitrary_amount	(mandatory) true/false. Indicates whether amount (described below) is set in the action, or whether it will be provided at the time of execution (i.e., when creating a transaction).  */
	public $arbitrary_amount;

	/* @var float $amount       amount of the action set in "USD" (min USD 0.01 , max USD 100) or branded token "BT" (min BT 0.00001, max BT 100). The transfer on-chain always occurs in branded token and fiat value is calculated to the equivalent amount of branded tokens at the moment of transfer. */
	public $amount;

	/* @var bool $arbitrary_commission	    Like 'arbitrary_amount' this attribute indicates whether commission_percent (described below) is set in the action, or whether it will be provided at the time of execution (i.e., when creating a transaction). */
	public $arbitrary_commission;

	/* @var float $commission_percent	    For user_to_user action you have an option to set commission percentage. The commission is inclusive in the amount and the complement goes to the OST partner company. Possible values (min 0%, max 100%) */
	public $commission_percent;

}