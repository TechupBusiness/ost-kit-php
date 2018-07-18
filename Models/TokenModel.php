<?php

namespace Techup\SimpleTokenApi\Models;


class TokenModel {

	/* @var string $company_uuid	unique identifier of the company */
	public $company_uuid;

	/* @var string $name	name of the token */
	public $name;

	/* @var string $symbol	name of the symbol */
	public $symbol;

	/* @var string $symbol_icon		icon reference */
	public $symbol_icon;

	/* @var float $conversion_factor		conversion factor of the branded token to OST */
	public $conversion_factor;

	/* @var string $token_erc20_address		prefixed hexstring address of the branded token erc20 contract on the utility chain */
	public $token_erc20_address;

	/* @var string $airdrop_contract_address		prefixed hexstring address of the airdrop contract */
	public $airdrop_contract_address;

	/* @var string $simple_stake_contract_address		prefixed hexstring address of the simple stake contract which holds the OST⍺ on Ethereum Ropsten testnet which has been staked to mint branded tokens */
	public $simple_stake_contract_address;

	/* @var float $total_supply		Total supply of Branded Tokens */
	public $total_supply;

	/* @var OstUtilityBalanceModel[] $ost_utility_balance		OST⍺ on utility chains with chain IDs and amounts */
	public $ost_utility_balance;

	/* @var array $price_points		Contains the OST price point in USD and the Branded Tokens price point in USD */
	public $price_points;

}