<?php

namespace Techup\SimpleTokenApi\Repositories;

use Techup\SimpleTokenApi\Enums\RequestTypeEnum;
use Techup\SimpleTokenApi\Enums\SortOrderEnum;
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
			'amount' => $this->roundAmount($amount),
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
	 * @param integer $page_no	                page number (starts from 1)
	 * @param string $order_by	                order the list by when the transaction was created (default) . Can only be ordered by transaction creation date.
	 * @param SortOrderEnum|string $sort_order	orders the list in 'desc' (default). Accepts value 'asc' to order in ascending order.
	 * @param integer $limit		            limits the number of transaction objects to be sent in one request. Possible Values Min 1, Max 100, Default 10.
	 * @param string[] $ids                     (optional) Airdrop id(s) to lookup airdrops
	 * @param string[] $current_statuses        (optional) Status(es) to lookup airdrops
	 *
	 * @throws
	 * @return AirdropModel[]
	 */
	public function list($page_no, $order_by, $sort_order, $limit, $ids = null, $current_statuses = null ) {

		$params = [
			'page_no' => $page_no,
			'order_by' => $order_by,
			'order' => $sort_order,
			'limit' => $limit,
			'id'  => $ids!==null && is_array($ids) ? implode(',', $ids) : null,
			'current_status'  => $current_statuses!==null && is_array($current_statuses) ? implode(',', $current_statuses) : null,
		];

		/* @var $list AirdropModel[] */
		$list = $this->request('/airdrops', $params, AirdropModel::class, RequestTypeEnum::GET() );

		return $list;
	}
}