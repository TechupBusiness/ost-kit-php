<?php

namespace Techup\SimpleTokenApi\Models;

class TransactionModel {

	/* @var string $id                  uuid of the transaction */
	public $id;

	/* @var string $from_user_id        uuid origin user of the branded token transaction */
	public $from_user_id;

	/* @var string $to_user_id	        uuid destination user of the branded token transaction */
	public $to_user_id;

	/* @var string $transaction_hash	hex-string, the generated transaction hash */
	public $transaction_hash;

	/* @var integer $action_id	        id of the action that was executed. */
	public $action_id;

	/* @var integer $timestamp	        universal time stamp value of execution of the transaction in milliseconds */
	public $timestamp;

	/* @var $status	string	            the execution status of the transaction type: "processing", "failed" or "complete" */
	public $status;

	/* @var float $gas_price	        value of the gas utilized for the transaction */
	public $gas_price;

	/* @var integer $gas_used	        (optional) hexadecimal value of the gas used to execute the tranaction */
	public $gas_used;

	/* @var float $transaction_fee	    (optional) the value of the gas used at the gas price */
	public $transaction_fee;

	/* @var integer $block_number	    (optional) the block on the chain in which the transaction was included */
	public $block_number;

	/* @var float $amount	            (optional) the amount of branded tokens transferred to the destination user */
	public $amount;

	/* @var float $commission_amount	(optional) the amount of branded tokens transferred to the company  */
	public $commission_amount;

}