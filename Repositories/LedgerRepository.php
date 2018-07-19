<?php

namespace Techup\SimpleTokenApi\Repositories;

use Techup\SimpleTokenApi\Enums\RequestTypeEnum;
use Techup\SimpleTokenApi\Enums\SortOrderEnum;
use Techup\SimpleTokenApi\Models\BalancesModel;
use Techup\SimpleTokenApi\Models\TransactionModel;

class LedgerRepository extends RepositoryAbstract {

	/**
	 * Get the balance for a user
	 *
	 * @param string        $uuid	        Uuid of the user
	 * @param integer       $page_no	    OPTIONAL: Page number (starts from 1)
	 * @param string        $order_by	    OPTIONAL: order the list by 'creation_time' or 'name' (default)
	 * @param SortOrderEnum $sort_order	    OPTIONAL: order users in 'desc' (default) or 'asc' order
	 * @param integer       $limit		    OPTIONAL: Limits the number of user objects to be sent in one request(min. 1, max. 100, default 10)
	 *
	 * @throws \Exception
	 *
	 * @return TransactionModel[]
	 */
	public function list($uuid, $page_no = null, $order_by = null, $sort_order = null, $limit = 10, $statuses = [] ) {

		$params = [
			'page_no' => $page_no,
			'order_by' => $order_by,
			'order' => $sort_order,
			'limit' => $limit,
			'status' => $statuses!==null && is_array($statuses) ? implode(',', $statuses) : null,
		];

		/* @var $balances BalancesModel[] */
		$transactions = $this->request('/ledger/'.$uuid, $params, TransactionModel::class, RequestTypeEnum::GET() );

		return $transactions;
	}

}