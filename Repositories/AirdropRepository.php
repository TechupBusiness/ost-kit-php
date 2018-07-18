<?php

namespace Techup\SimpleTokenApi\Repositories;

use Techup\SimpleTokenApi\Enums\RequestTypeEnum;
use Techup\SimpleTokenApi\Models\AirdropModel;

class AirdropRepository extends RepositoryAbstract {

	/**
	 * @param float $amount         (mandatory) The amount of BT that needs to be air-dropped to the selected end-users. Example:10
	 * @param bool $airdropped      true/false. Indicates whether to airdrop tokens to end-users who have been airdropped some tokens at least once or to end-users who have never been airdropped tokens.
	 * @param string[] $user_ids    a comma-separated list of user_ids specifies selected users in the token economy to be air-dropped tokens to.
	 *
	 * VALUE IN AIRDROPPED	        VALUE IN USER_IDS	                        EXPECTED BEHAVIOR
	 * true	                        comma-separated list of user ids	        Extracts a list of all users you have been airdropped tokens at least once. Further refines the list to specific user ids passed in parameter 'user_ids'. This refined list is sent the tokens specified in the 'amount' parameter.
	 * true	                        user_ids are not sent in the api request	Extracts a list of all users you have been airdropped tokens at least once. This list is sent the tokens specified in the 'amount' parameter.
	 * false	                    comma-separated list of user ids	        Extracts a list of all users you have never been airdropped tokens further refines the list to specific user ids passed in parameter 'user_ids'. This refined list is sent the tokens specified in the 'amount' parameter.
	 * false	                    user_ids are not sent in the api request	Extracts a list of all users you have never been airdropped tokens. This list is sent the tokens specified in the 'amount' parameter.
	 * airdropped value is not set	comma-separated list of user ids	        The list to specific user ids is sent the tokens specified in the 'amount' parameter.
	 * airdropped value is not set	user_ids are not sent in the api request	ALL users are sent the tokens specified in the 'amount' parameter.
	 *
	 *
	 * @return AirdropModel
	 * @throws \Exception
	 */
	public function execute($amount, $airdropped = null, $user_ids = []) {

		$params = [
			'amount' => $amount,
			'airdropped' => $airdropped,
			'user_ids' => implode(',', $user_ids),
		];

		/* @var $execute AirdropModel[] */
		$execute = $this->request('/airdrops', $params, AirdropModel::class, RequestTypeEnum::POST() );

		return array_shift($execute);
	}

	/**
	 * Get an airdrop
	 *
	 * @throws \Exception
	 * @return AirdropModel
	 */
	public function get($uuid) {
		/* @var $status AirdropModel[] */
		$status = $this->request('/airdrops/'.$uuid, [], AirdropModel::class, RequestTypeEnum::POST() );
		return array_shift($status);
	}

	/**
	 *
	 * @param integer $page_no	    page number (starts from 1)
	 * @param string $order_by	    order the list by when the transaction was created (default) . Can only be ordered by transaction creation date.
	 * @param string $order	        orders the list in 'desc' (default). Accepts value 'asc' to order in ascending order.
	 * @param integer $limit		limits the number of transaction objects to be sent in one request. Possible Values Min 1, Max 100, Default 10.
	 * @param string $optional__filters	filters can be used to refine your list. The Parameters on which filters are supported are:
	 *      Filter:         Description:                                                Example:
	 *      id	            Airdrop ids	                                                'id="bc6dc9e1-6e62-4032-8862-6f664d8d7541, 94543988-9fa6-4d0a-8a9f-d65d345f6175"'
	 *      current_status	indicates the stage at which the executed airdrop is in.	'current_status="complete, pending"'
	 *
	 * @throws
	 * @return AirdropModel[]
	 */
	public function list($page_no, $order_by, $order, $limit, $optional__filters="" ) {

		$params = [
			'page_no' => $page_no,
			'order_by' => $order_by,
			'order' => $order,
			'limit' => $limit,
			'optional__filters' => $optional__filters,
		];

		/* @var $list AirdropModel[] */
		$list = $this->request('/airdrops', $params, AirdropModel::class, RequestTypeEnum::GET() );

		return $list;
	}
}