<?php

namespace Techup\SimpleTokenApi\Models;


class OstUtilityBalanceModel {
	/* @var int $chain_id	Chain ID */
	public $chain_id;

	/* @var float $amount	amount */
	public $amount;

	/**
	 * OstUtilityBalanceModel constructor.
	 * We need to give the data via constructor because the API does not return string keys
	 *
	 * @param array $data
	 */
	function __construct($data=[]) {
		if(count($data)>0) {
			$this->chain_id = $data[0];
			$this->amount = $data[1];
		}
	}
}