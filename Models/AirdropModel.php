<?php

namespace Techup\SimpleTokenApi\Models;


class AirdropModel {
	/** @var string $id		uuid identifier for the airdrop. */
	public $id;

	/** @var string $current 	incomplete|pending|failed|complete  */
	public $current;

	/** @var string[] $steps    user_identified|tokens_transferred|contract_approved|allocation_done  */
	public $steps;
}