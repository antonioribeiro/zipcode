<?php

namespace PragmaRX\Zip\Support;

use GuzzleHttp\Client as Guzzle;

class Http {

	public function access($url)
	{
		$client = new \GuzzleHttp\Client();

		$response = $client->get($url);

		if ($response->getStatusCode() != 200)
		{
			return false;
		}

		return json_decode($response->getBody(), true);
	}

	public function ping($url)
	{
		return true;
	}

	public function consume($url)
	{
		return $this->access($url);
	}

}