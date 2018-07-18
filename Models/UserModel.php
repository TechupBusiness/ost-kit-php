<?php

namespace Techup\SimpleTokenApi\Models;


class UserModel {

	/* @var string $id	user uuid */
	public $id;

	/* @var AddressModel[]|string $addresses	addresses of the user, string is for parsing the return properly */
	public $addresses = AddressModel::class;

	/* @var string $name	name of the user (not unique) */
	public $name;

	/* @var float $airdropped_tokens	total amount of airdropped tokens to the user */
	public $airdropped_tokens;

	/* @var float $token_balance	number current balance of the user */
	public $token_balance;

}