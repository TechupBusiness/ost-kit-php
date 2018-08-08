<?php

namespace Techup\SimpleTokenApi\Repositories;

use Techup\SimpleTokenApi\Enums\KindEnum;
use Techup\SimpleTokenApi\Enums\RequestTypeEnum;
use Techup\SimpleTokenApi\Enums\SortOrderEnum;
use Techup\SimpleTokenApi\Models\ActionModel;

class ActionRepository extends RepositoryAbstract {

	/**
	 * @param string $name                  (mandatory) unique name of the action
	 * @param KindEnum $kind                An action can be one of three kinds: "user_to_user", "company_to_user", or "user_to_company" to clearly determine whether value flows within the application or from or to the company.
	 * @param string $currency              (mandatory) type of currency the action amount is specified in. Possible values are "USD" (fixed) or "BT" (floating). When an action is set in fiat the equivalent amount of branded tokens are calculated on-chain over a price oracle. For OST KIT⍺ price points are calculated by and taken from coinmarketcap.com and published to the contract by OST.com.
	 * @param bool $arbitrary_amount        (mandatory) true/false. Indicates whether amount (described below) is set in the action, or whether it will be provided at the time of execution (i.e., when creating a transaction).
	 * @param float $amount                 amount of the action set in "USD" (min USD 0.01 , max USD 100) or branded token "BT" (min BT 0.00001, max BT 100). The transfer on-chain always occurs in branded token and fiat value is calculated to the equivalent amount of branded tokens at the moment of transfer.
	 * @param bool $arbitrary_commission    true/false. Like 'arbitrary_amount' this attribute indicates whether commission_percent (described below) is set in the action, or whether it will be provided at the time of execution (i.e., when creating a transaction).
	 * @param float $commission_percent    for user_to_user action you have an option to set commission percentage. The commission is inclusive in the amount and the percentage of the amount goes to the OST partner company. Possible values (min 0%, max 100%)
	 *
	 * @return ActionModel
	 * @throws \Exception
	 */
	public function create($name, $kind, $currency, $arbitrary_amount, $amount, $arbitrary_commission = null, $commission_percent = null) {

		$params = [
			'name' => substr($name,0,20),
			'kind' => $kind,
			'currency' => $currency,
			'arbitrary_amount' => $arbitrary_amount,
			'amount' => $this->roundAmount($amount),
			'arbitrary_commission' => $arbitrary_commission,
		];

		if($kind == KindEnum::user_to_user() && $arbitrary_commission===false) {
			$params['commission_percent'] = $commission_percent;
		}
		if($arbitrary_amount==true) {
			unset($params['amount']);
		}

		/* @var $execute ActionModel[] */
		$execute = $this->request('/actions', $params , ActionModel::class, RequestTypeEnum::POST() );

		return array_shift($execute);
	}

	/**
	 * @param int       $id                    Action to update
	 * @param string    $name                  Unique name of the action
	 * @param KindEnum  $kind                  An action can be one of three kinds: "user_to_user", "company_to_user", or "user_to_company" to clearly determine whether value flows within the application or from or to the company.
	 * @param string    $currency              type of currency the action amount is specified in. Possible values are "USD" (fixed) or "BT" (floating). When an action is set in fiat the equivalent amount of branded tokens are calculated on-chain over a price oracle. For OST KIT⍺ price points are calculated by and taken from coinmarketcap.com and published to the contract by OST.com.
	 * @param bool      $arbitrary_amount      true/false. Indicates whether amount (described below) is set in the action, or whether it will be provided at the time of execution (i.e., when creating a transaction).
	 * @param float     $amount                amount of the action set in "USD" (min USD 0.01 , max USD 100) or branded token "BT" (min BT 0.00001, max BT 100). The transfer on-chain always occurs in branded token and fiat value is calculated to the equivalent amount of branded tokens at the moment of transfer.
	 * @param bool      $arbitrary_commission  true/false. Like 'arbitrary_amount' this attribute indicates whether commission_percent (described below) is set in the action, or whether it will be provided at the time of execution (i.e., when creating a transaction).
	 * @param float     $commission_percent    for user_to_user action you have an option to set commission percentage. The commission is inclusive in the amount and the percentage of the amount goes to the OST partner company. Possible values (min 0%, max 100%)
	 *
	 * @return ActionModel
	 * @throws \Exception
	 */
	public function update($id, $name=null, $kind=null, $currency=null, $arbitrary_amount=null, $amount=null, $arbitrary_commission=null, $commission_percent=null) {
		$params = [];

		if($name!==null)                    $params['name'] = $name;
		if($kind!==null)                    $params['kind'] = $kind;
		if($currency!==null)                $params['currency'] = $currency;
		if($arbitrary_amount!==null)        $params['arbitrary_amount'] = $arbitrary_amount;
		if($amount!==null)                  $params['amount'] = $this->roundAmount($amount);
		if( $arbitrary_commission !== null)    $params['arbitraty_commission'] = $arbitrary_commission;
		if($commission_percent!==null)      $params['commission_percent'] = $commission_percent;

		if(count($params)>0) {
			/* @var $execute ActionModel */
			$execute = $this->request( '/actions/'.$id, $params, ActionModel::class, RequestTypeEnum::POST() );
			return array_shift($execute);
		} else {
			return null;
		}

	}

	/**
	 * @param integer $id
	 *
	 * @return ActionModel
	 * @throws \Exception
	 */
	public function get($id) {

		/* @var $get ActionModel[] */
		$get = $this->request('/actions/'.$id, [
		], ActionModel::class, RequestTypeEnum::GET() );

		return array_shift($get);
	}

	/**
	 *
	 * @param integer       $page_no	            page number (starts from 1)
	 * @param string        $order_by	            (optional) order the list by 'creation_time' or 'name' (default)
	 * @param SortOrderEnum $sort_order             (optional) order users in 'desc' (default) or 'asc' order
     * @param integer       $limit		            limits the number of user objects to be sent in one request(min. 1, max. 100, default 10)
	 * @param string[]      $ids                    (optional) Filter for Action ids
	 * @param string[]      $names                  (optional) Filter for names of the action
	 * @param KindEnum      $kind                   (optional) Filter for the kind of the action set during the creation of the action
	 * @param bool          $arbitrary_amount       (optional) Filter for actions where the amount is set during creation or provided at execution
	 * @param bool          $arbitrary_commission   (optional) Filter for user_to_user actions where the commission is set during creation or provided at execution
	 *
	 * @throws
	 * @return ActionModel[]
	 */
	public function list($page_no, $order_by = null, $sort_order = null, $limit = null, $ids=null, $names=null, $kind=null, $arbitrary_amount=null, $arbitrary_commission=null ) {

		$params = [
			'page_no' => $page_no,
			'order_by' => $order_by,
			'order' => $sort_order,
			'limit' => $limit,
			'id'  => $ids!==null && is_array($ids) ? implode(',', $ids) : null,
			'name'  => $names!==null && is_array($names) ? implode(',', $names) : null,
			'kind' => $kind,
			'arbitrary_amount' => $arbitrary_amount,
			'arbitrary_commission' => $arbitrary_commission,
		];

		/* @var $list ActionModel[] */
		$list = $this->request('/actions', $params, ActionModel::class, RequestTypeEnum::GET() );

		return $list;
	}
}