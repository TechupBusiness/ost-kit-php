<?php

namespace Techup\SimpleTokenApi\Repositories;

use Techup\SimpleTokenApi\Enums\RequestTypeEnum;
use Techup\SimpleTokenApi\Models\PricePointsModel;
use Techup\SimpleTokenApi\Models\TokenModel;

class TokenRepository extends RepositoryAbstract {

	/**
	 * Returns infos about the token economy
	 *
	 * @throws \Exception
	 *
	 * @return TokenModel
	 */
	public function info() {
		/* @var $user TokenModel[] */
		$info = $this->request('/token', [ ], [ TokenModel::class, PricePointsModel::class ], RequestTypeEnum::GET() );
		return $info;
	}

	/**
	 * @param $info
	 *
	 * @return PricePointsModel
	 */
	public function filterPricePoints($info) {
		return $info[PricePointsModel::class][0];
	}

	/**
	 * @param $info
	 *
	 * @return TokenModel
	 */
	public function filterToken($info) {
		return $info[TokenModel::class][0];
	}

}