<?php

namespace Techup\SimpleTokenApi\Repositories;

use Techup\SimpleTokenApi\Enums\RequestTypeEnum;

abstract class RepositoryAbstract {

	protected $rest;

	private $api_key;
	private $api_secret;
	private $company_uuid;

	/**
	 * WrapperAbstract constructor.
	 *
	 * @param $mode
	 * @param $api_key
	 * @param $api_secret
	 * @param $company_uuid
	 */
	public function __construct($mode, $api_key, $api_secret, $company_uuid ) {

		$this->api_key = $api_key;
		$this->api_secret = $api_secret;
		$this->company_uuid = $company_uuid;

		if($mode=="live") {
			$url = '';
		} else {
			$url = 'https://sandboxapi.ost.com/v1';
		}

		// check for cs cart
		if (!defined('BOOTSTRAP')) {
			$this->rest = new PestWrapper( $url );
		} else {
			$this->rest = new \Tygh\RestClient( $url );
		}
	}

	/**
	 * Sends a request
	 *
	 * @param string $endpoint
	 * @param (array|string)[] $parameters
	 * @param string[]|string $class_name
	 * @param RequestTypeEnum $request_type must be get, post or put
	 * @param array $meta   Get meta values by reference passing
	 *
	 * @return object|array
	 * @throws \Exception
	 */
	protected function request($endpoint, $parameters, $class_names, $request_type) {

		if(!is_array($class_names)) {
			$class_names = [ $class_names ];
		}

		// Clean parameters of null values
		$pTest = $parameters;
		foreach($pTest as $k=>$p) {
			if($p===null) {
				unset($parameters[$k]);
			}
		}

		$request_type = strtolower($request_type);

		list($query_string, $query_data) = $this->buildQueryData($endpoint, $parameters);

		if(!in_array($request_type, [ 'post','get','put', 'delete' ])) {
			throw new \Exception('Not supported request_type used ('.$request_type.') for '.$query_string);
		}

		switch($request_type) {
			case 'post':
				$result = $this->rest->$request_type($endpoint, $query_data);
				break;
			default:
				$result = $this->rest->$request_type($query_string);
		}

		if(!empty($result)) {
			if($result['success']==true) {
				if(count($class_names)==1) {
					return $this->parseResult($result['data'], $class_names[0]);
				} else {
					$return = [];
					foreach($class_names as $c) {
						$return[$c] = $this->parseResult($result['data'], $c);
					}
					return $return;
				}
			} else {
				/* Examples
				{
					"code": "companyRestFulApi(401:HJg2HK0A_f)",
					"msg": "Unauthorized",
					"error_data": {}
				}


				{
					"code": "companyRestFulApi(s_a_g_1:rJndQJkYG)",
					"msg": "invalid params",
					"error_data": [
					  {
						"name": "Only letters, numbers and spaces allowed. (Max 20 characters)"
					  }
					]
				}
				*/

				throw new \Exception($result['data']['code']. ': '. $result['data']['msg'] . (count($result['data']['error_data'])>0 ? ' ('. join(array_map(function($v) { return $v['name']; }, $result['data']['error_data'])) .')' : ''));
			}
		}

		throw new \Exception('No data returned for '.$query_string);
	}

	/**
	 * Builds and signs the query and add all needed parameters (api_key, request_timestamp, signature)
	 *
	 * @param string $endpoint
	 * @param array $parameters
	 * @param string $method 'get' or 'post'
	 *
	 * @return array[]
	 * @throws \Exception
	 */
	protected function buildQueryData($endpoint, $parameters) {

		$parameters['api_key'] = $this->api_key;
		$parameters['request_timestamp'] = time();
		ksort($parameters);

		$toDeleteParams = [ ];
		foreach($parameters as $key=>$val) {
			if(is_array($val)) {
				$toDeleteParams[] = $key;
				$key .= '[]';
				foreach($val as $v) {
					$query_params[] = strtolower($key) . '=' . urlencode($v);
				}
				$parameters[$key] = $val;
			}
			else {
				if(is_bool($val)) {
					$val = $val ? 'true' : 'false';
				}
				$query_params[] = strtolower($key) . '=' . urlencode($val);
				$parameters[$key] = strval($val);
			}
		}
		foreach($toDeleteParams as $del) {
			unset($parameters[$del]);
		}
		ksort($parameters);

		$query = $endpoint . '?' . implode('&', $query_params);

		$signature = urlencode ( hash_hmac ('sha256', $query, $this->api_secret) );

		$parameters['signature'] = $signature;
		$query .= '&signature='.urlencode($signature);

		return [ $query, $parameters ];
	}

	/**
	 * Parse the result of REST body (for result_type) and create new object(s) from class_name
	 *
	 * @param array $data
	 * @param string $class_name
	 * @return object[]
	 */
	protected function parseResult($data, $class_name) {

		$test = new $class_name();
		if(property_exists($test, 'result_type')) {
			$type = $test->result_type;
		} else {
			$type = $data['result_type'];
		}

		$return = [];

		if(is_array($data[$type])) {
			$payloads = $data[$type];
			if(!$this->isObjectList($payloads)) {
				$payloads = [ $payloads ];
			}

			foreach( $payloads as $payload ) {
				$c = new $class_name();
				foreach ( $payload as $key => $val ) {
					if ( property_exists ( $c , $key ) ) {
						$prop_value = $c->$key;
						if(!empty($prop_value) && substr($prop_value,0,1)==='\\' && class_exists($prop_value)) {
							if($this->isObjectList($val)) {
								$c->$key = [ ];
								foreach($val as $obj) {
									$c->$key[] = new $prop_value( $obj );
								}
							} else {
								$c->$key = new $prop_value( $val );
							}
						} else {
							$c->$key = $val;
						}
					}
				}
				$return[] = $c;
			}
		}

		return $return;
	}


	protected function isObjectList($data) {
		if(is_array($data)) {
			$hasNonArray = false;
			$keys = array_keys($data);
			foreach ($keys as $key) {
				if(!is_numeric($key)) {
					return false;
				}
				if(!is_array($data[$key])) {
					$hasNonArray = true;
				}
			}
			return !$hasNonArray;
		}
		return false;
	}

	/**
	 * Extracts parameters automatically
	 *
	 * @internal Not used at the moment. Use for later
	 *
	 * @param $class_name
	 * @param $method_name
	 *
	 * @throws \ReflectionException
	 *
	 * @return array
	 */
	protected function extractParameters($class_name, $method_name) {
		$arr = array();
		$ref = new \ReflectionMethod($class_name, $method_name );

		foreach($ref->getParameters() as $parameter)
		{
			$name = $parameter->getName();
			$arr[$name] = ${$name};
		}
		return $arr;
	}

}