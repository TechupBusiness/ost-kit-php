<?php

namespace Techup\SimpleTokenApi\Models;


class BalancesModel {

	/* @var float $available_balance	current available balance of the user in BT (airdropped_balance + token_balance) */
	public $available_balance;

	/* @var float $airdropped_balance	current balance of tokens that were airdropped to the user in BT */
	public $airdropped_balance;

	/* @var float $token_balance	    current balance of tokens in BT that users have earned within your branded token economy by performing the respective actions you defined. */
	public $token_balance;

}