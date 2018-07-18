<?php

namespace Techup\SimpleTokenApi\Repositories;

use Techup\SimpleTokenApi\Enums\RequestTypeEnum;
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
	 * Get a user (workaround to get user data is an edit action)
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
	 *
	 * @param integer $page_no	    page number (starts from 1)
	 * @param bool $airdropped      true == users who have been airdropped tokens, false == users who have not been airdropped tokens
	 * @param integer $limit		limits the number of user objects to be sent in one request(min. 1, max. 100, default 10)
	 * @param string $order_by	    (optional) order the list by 'creation_time' or 'name' (default)
	 * @param string $order	        (optional) order users in 'desc' (default) or 'asc' order
	 * @param string $optional__filters	filters can be used to refine your list. The Parameters on which filters are supported are:
	 *      Filter:     Description:            Example:
	 *      id	        user ids	            'id="3b679b8b-b56d-48e5-bbbe-7397899c8ca6, d1c0be68-30bd-4b06-af73-7da110dc62da"'
	 *      name	    specific user names	    'name="Alice, Bob"'
	 *
	 * @throws
	 * @return UserModel[]
	 */
	public function list($page_no, $airdropped, $limit = 10, $order_by = '', $order = '', $optional__filters="" ) {

		$params = [
			'page_no' => $page_no,
			'order_by' => $order_by,
			'order' => $order,
			'limit' => $limit,
			'optional__filters' => $optional__filters,
		];

		/* @var $list UserModel[] */
		$list = $this->request('/users', $params, UserModel::class, RequestTypeEnum::GET() );

		return $list;
	}

}