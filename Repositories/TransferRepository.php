<?php

namespace Techup\SimpleTokenApi\Repositories;

use Techup\SimpleTokenApi\Enums\RequestTypeEnum;
use Techup\SimpleTokenApi\Enums\SortOrderEnum;
use Techup\SimpleTokenApi\Models\TransferModel;

class TransferRepository extends RepositoryAbstract {

	/**
	 * Creates a transfer
	 * @param string    $to_address	    Public address (hexstring) to which to transfer OST⍺ Prime
	 * @param integer   $amount	        Amount of OST⍺ Prime to transfer in Wei; should be between 0 and 10^20, exclusive	 *
	 * @return TransferModel
	 * @throws \Exception
	 */
	public function create($to_address, $amount) {

		$params = [
			'to_address' => $to_address,
			'amount' => $this->roundAmount($amount),
		];

		/* @var $execute TransferModel[] */
		$execute = $this->request('/transfers', $params , TransferModel::class, RequestTypeEnum::POST() );

		return array_shift($execute);
	}

	/**
	 * Gets a transfer
	 * @param integer $id
	 *
	 * @return TransferModel
	 * @throws \Exception
	 */
	public function get($id) {

		/* @var $get TransferModel[] */
		$get = $this->request('/transfers/'.$id, [
		], TransferModel::class, RequestTypeEnum::GET() );

		return array_shift($get);
	}

	/**
	 * Returns a list of transfers
	 *
	 * @param integer       $page_no	   OPTIONAL: page number (starts from 1)
	 * @param string        $order_by	   OPTIONAL: order the list by 'creation_time' or 'name' (default)
	 * @param SortOrderEnum $sort_order    OPTIONAL: order users in 'desc' (default) or 'asc' order
     * @param integer       $limit		   OPTIONAL: limits the number of user objects to be sent in one request(min. 1, max. 100, default 10)
	 * @param string[]      $ids           OPTIONAL: Filter for Action ids
	 *
	 * @throws
	 *
	 * @return TransferModel[]
	 */
	public function list($page_no = 1, $order_by = null, $sort_order = null, $limit = null, $ids=null ) {

		$params = [
			'page_no' => $page_no,
			'order_by' => $order_by,
			'order' => $sort_order,
			'limit' => $limit,
			'id'  => $ids!==null && is_array($ids) ? implode(',', $ids) : null,
		];

		/* @var $list TransferModel[] */
		$list = $this->request('/actions', $params, TransferModel::class, RequestTypeEnum::GET() );

		return $list;
	}
}