<?php

namespace Techup\SimpleTokenApi\Repositories;

use Techup\SimpleTokenApi\Enums\RequestTypeEnum;
use Techup\SimpleTokenApi\Models\BalancesModel;

class BalancesRepository extends RepositoryAbstract {

	/**
	 * Get the balance for a user
	 *
	 * @param string $uuid	Uuid of the user
	 *
	 * @throws \Exception
	 *
	 * @return BalancesModel
	 */
	public function get($uuid) {

		/* @var $balances BalancesModel[] */
		$balances = $this->request('/balances/'.$uuid, [
		], BalancesModel::class, RequestTypeEnum::GET() );

		return array_shift($balances);
	}

}