<?php

namespace PragmaRX\Zip\Support;

use GuzzleHttp\Client as Guzzle;

class Http {

	public function access()
	{

		$client = new GuzzleHttp\Client();
		$response = $client->get('http://guzzlephp.org');
		$res = $client->get('https://api.github.com/user', ['auth' =>  ['user', 'pass']]);
		echo $res->getStatusCode();
		// 200
		echo $res->getHeader('content-type');
		// 'application/json; charset=utf8'
		echo $res->getBody();
		// {"type":"User"...'
		var_export($res->json());
		// Outputs the JSON decoded data

	}

	public function ping($url)
	{
		return true;
	}

}