<?php

namespace Techup\SimpleTokenApi\Repositories;

use Techup\SimpleTokenApi\Enums\RequestTypeEnum;
use Techup\SimpleTokenApi\Models\TransactionModel;

class TransactionRepository extends RepositoryAbstract {

	/**
	 * @param $from_user_id
	 * @param $to_user_id
	 * @param $action_id
	 * @param $amount
	 * @param $commission_percent
	 *
	 * @return TransactionModel
	 * @throws \Exception
	 */
	public function execute($from_user_id, $to_user_id, $action_id, $amount = null, $commission_percent = null ) {

		$params = [
			'from_user_id' => $from_user_id,
			'to_user_id' => $to_user_id,
			'action_id' => $action_id,
			'amount' => $amount,
			'commission_percent' => $commission_percent,
		];

		/* @var $execute TransactionModel[] */
		$execute = $this->request('/transactions', $params, TransactionModel::class, RequestTypeEnum::POST() );

		return array_shift($execute);
	}

	/**
	 * Get a transaction
	 *
	 * @throws \Exception
	 * @return TransactionModel
	 */
	public function get($uuid) {
		/* @var $status TransactionModel[] */
		$status = $this->request('/transactions/'.$uuid, [], TransactionModel::class, RequestTypeEnum::GET() );
		return array_shift($status);
	}

	/**
	 *
	 * @param integer $page_no	    page number (starts from 1)
	 * @param string $order_by	    order the list by when the transaction was created (default) . Can only be ordered by transaction creation date.
	 * @param string $order	        orders the list in 'desc' (default). Accepts value 'asc' to order in ascending order.
	 * @param integer $limit		limits the number of transaction objects to be sent in one request. Possible Values Min 1, Max 100, Default 10.
	 * @param string $optional__filters	filters can be used to refine your list. The Parameters on which filters are supported are:
	 *      Filter:     Description:        Example:
	 *      id	        Transaction ids	    'id="e1f95fcb-5853-453a-a9b3-d4f7a38d5beb, e7800825-fd24-4574-b7a6-06472ca1ef9d"'
	 *
	 * @throws
	 * @return TransactionModel[]
	 */
	public function list($page_no, $order_by, $order, $limit, $optional__filters="" ) {

		$params = [
			'page_no' => $page_no,
			'order_by' => $order_by,
			'order' => $order,
			'limit' => $limit,
			'optional__filters' => $optional__filters,
		];

		/* @var $list TransactionModel[] */
		$list = $this->request('/transactions', $params, TransactionModel::class, RequestTypeEnum::GET() );

		return $list;
	}
}