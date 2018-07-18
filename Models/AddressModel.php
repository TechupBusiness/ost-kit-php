<?php

namespace Techup\SimpleTokenApi\Models;


class AddressModel {

	/* @var int $chain_id	Chain ID */
	public $chain_id;

	/* @var string $address	Hex string address */
	public $address;

	/**
	 * AddressModel constructor.
	 * We need to give the data via constructor because the API does not return string keys
	 *
	 * @param array $data
	 */
	function __construct($data=[]) {
		if(count($data)>0) {
			$this->chain_id = $data[0];
			$this->address = $data[1];
		}
	}
}