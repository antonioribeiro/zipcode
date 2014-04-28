<?php

namespace PragmaRX\ZIPcode\Support;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\RequestException;

class Http implements HttpInterface
{

	/**
	 * User agent internal string.
	 *
	 * @var string
	 */
	private $userAgent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.64 Safari/537.11';

	/**
	 * The Guzzle instance.
	 *
	 * @var
	 */
	private $guzzle;

	/**
	 * User agent getter.
	 *
	 * @return mixed
	 */
	public function getUserAgent()
	{
		return $this->userAgent;
	}

	/**
	 * User agent setter.
	 *
	 * @param mixed $userAgent
	 */
	public function setUserAgent($userAgent)
	{
		$this->userAgent = $userAgent;
	}

	/**
	 * Consume an url.
	 *
	 * @param $url
	 * @return bool|mixed
	 */
	public function consume($url)
	{
		$this->instantiateGuzzle();

		try
		{
			$response = $this->guzzle->get($url);
		}
		catch(RequestException $e)
		{
			return false;
		}

		return $response->getStatusCode() != 200
				? false
				: $this->decode($response->getBody());
	}

	/**
	 * Decode a request result.
	 *
	 * @param $body
	 * @return mixed
	 */
	private function decode($body)
	{
		if (is_json($body))
		{
			return json_decode($body, true);
		}
		else
		if (is_xml($body))
		{
			return xml_to_json($body);
		}

		return $body;
	}

	/**
	 * Creates a Guzzle instance.
	 *
	 */
	public function instantiateGuzzle()
	{
		if (isset($this->guzzle))
		{
			unset($this->guzzle);
		}

		$this->guzzle = new Guzzle([
			'defaults' => [
				'headers' => ['User-Agent' => $this->getUserAgent()]
			]
		]);
	}

}