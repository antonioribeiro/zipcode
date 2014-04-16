<?php

namespace PragmaRX\Zip\Support;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\AdapterException;

class Http {

	private $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.64 Safari/537.11';

	/**
	 * @return mixed
	 */
	public function getUserAgent()
	{
		return $this->userAgent;
	}

	/**
	 * @param mixed $userAgent
	 */
	public function setUserAgent($userAgent)
	{
		$this->userAgent = $userAgent;
	}

	public function access($url, $resultType)
	{
		$client = new \GuzzleHttp\Client();

		$client = new \GuzzleHttp\Client(array(
		    'defaults' => array(
			    'headers'         => array('User-Agent' => $this->getUserAgent())
			)
		));

		try
		{
			$response = $client->get($url);
		}
		catch(\GuzzleHttp\Exception\RequestException $e)
		{
			return false;
		}

		if ($response->getStatusCode() != 200)
		{
			return false;
		}

		return $this->decode($response->getBody(), $resultType);
	}

	public function ping($url)
	{
		return true;
	}

	public function consume($url, $resultType)
	{
		return $this->access($url, $resultType);
	}

	private function decode($body, $resultType)
	{
		if ($resultType == 'json')
		{
			return json_decode($body, true);
		}
		else
		if ($resultType == 'xml')
		{
			return $this->xmlToJson($body);
		}

		return $body;
	}

	private function xmlToJson($text)
	{
		$xml = simplexml_load_string($text);

		$json = json_encode($xml);

		return json_decode($json,TRUE);
	}

}