<?php

namespace Techup\SimpleTokenApi\Models;

use Techup\SimpleTokenApi\Enums\StatusEnum;

class TransferModel {

	/* @var string      $id	            identifier for the transfer object */
	public $id;

	/* @var string      $from_address	token economy reserve address that is controlled by OST KIT⍺ from which OST⍺ Prime is transferred */
	public $from_address;

	/* @var string      $to_address		address to which to transfer OST⍺ Prime */
	public $to_address;

	/* @var integer     $amount	        integer	amount of OST⍺ Prime to transfer in Wei */
	public $amount;

	/* @var string      $transaction_hash	the generated transaction hash (null, initially) */
	public $transaction_hash;

	/* @var integer     $timestamp	    epoch time in milliseconds of current time */
	public $timestamp;

	/* @var StatusEnum|string   $status  the execution status of the transfer: "processing", "failed" or "complete" */
	public $status;

	/* @var float       $gas_price		value of the gas utilized for the transfer */
	public $gas_price;

	/* @var string      $gas_used		OPTIONAL: hexadecimal value of the gas used to execute the transfer (null, initially) */
	public $gas_used;

	/* @var integer     $block_number	OPTIONAL: the block on the chain in which the transfer was included (null, initially) */
	public $block_number;

	/* @var integer     $chain_id	    integer	the identifier of the chain to which the transfer transaction was sent */
	public $chain_id;

}