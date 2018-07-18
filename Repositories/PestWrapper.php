<?php

namespace Techup\SimpleTokenApi\Repositories;

class PestWrapper
{
	public $pest;

	public function __construct($url)
	{
		$this->pest = new \PestJSON($url);
	}

	public function get($url, $data = array())
	{
		return $this->request('get', $url, $data);
	}

	public function post($url, $data = array())
	{
		return $this->request('post', $url, $data);
	}

	public function put($url, $data = array())
	{
		return $this->request('put', $url, $data);
	}

	public function delete($url)
	{
		return $this->request('delete', $url);
	}

	protected function request($method, $url, $data = array())
	{
		$res = $this->pest->$method($url, $data);
		return $res;
	}

}
