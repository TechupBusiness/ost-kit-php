<?php

namespace Techup\SimpleTokenApi\Repositories;

use Techup\SimpleTokenApi\Enums\RequestTypeEnum;
use Techup\SimpleTokenApi\Enums\SortOrderEnum;
use Techup\SimpleTokenApi\Models\UserModel;

class UserRepository extends RepositoryAbstract {

	/**
	 * Creates a user
	 *
	 * @param string $name	name of the user
	 *
	 * @throws \Exception
	 *
	 * @return UserModel
	 */
	public function create($name) {

		/* @var $user UserModel[] */
		$user = $this->request('/users', [
			'name' => substr($name,0,20),
		], UserModel::class, RequestTypeEnum::POST() );

		return array_shift($user);
	}

	/**
	 * Get a user
	 *
	 * @param string $uuid	Uuid of the user
	 *
	 * @throws \Exception
	 *
	 * @return UserModel
	 */
	public function get($uuid) {

		/* @var $user UserModel[] */
		$user = $this->request('/users/'.$uuid, [
		], UserModel::class, RequestTypeEnum::GET() );

		return array_shift($user);
	}

	/**
	 * List of user
	 *
	 * @param integer $page_no	            page number (starts from 1)
	 * @param bool $airdropped              true == users who have been airdropped tokens, false == users who have not been airdropped tokens
	 * @param integer $limit		        limits the number of user objects to be sent in one request(min. 1, max. 100, default 10)
	 * @param string $order_by	            (optional) order the list by 'creation_time' or 'name' (default)
	 * @param SortOrderEnum $sort_order	    (optional) order users in 'desc' (default) or 'asc' order
	 * @param string[] $ids                 (optional) user id(s) to lookup user
	 * @param string[] $names               (optional) specific name(s) to lookup user
	 *
	 * @throws
	 * @return UserModel[]
	 */
	public function list($page_no, $airdropped, $limit = 10, $order_by = null, $sort_order = null, $ids = null, $names = null ) {

		$params = [
			'page_no' => $page_no,
			'order_by' => $order_by,
			'order' => $sort_order,
			'limit' => $limit,
			'id'    => $ids!==null && is_array($ids) ? implode(',', $ids) : null,
			'name'  => $names!==null && is_array($names) ? implode(',', $names) : null,
		];

		/* @var $list UserModel[] */
		$list = $this->request('/users', $params, UserModel::class, RequestTypeEnum::GET() );

		return $list;
	}

}