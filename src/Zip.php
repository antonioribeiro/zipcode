<?php

namespace PragmaRX\Zip;

use PragmaRX\Zip\Exceptions\InvalidZip;
use PragmaRX\Zip\Support\Http;

class Zip
{
	private $providers = array(
		array(
			'url' => 'http://republicavirtual.com.br/web_cep.php',
			'query' => '?cep=%s&formato=json'
		),
	);

	private $http;

	public function __construct(Http $http)
	{
		$this->http = $http;
	}

	public function setZip($zip)
	{
		if ( ! $this->validateZip($zip))
		{
			throw new InvalidZip("Zip code '$zip' is not valid.", 1);
		}
	}

	public function validateZip($zip)
	{
		$zip = preg_replace("/[^0-9]/", "", $zip);

		return is_numeric($zip) && strlen($zip) === 8;
	}

	public function checkZipProviders()
	{
		foreach($this->getProviders() as $provider)
		{
			if ( ! $this->http->ping($provider['url']))
			{
				return false;
			}
		}

		return true;
	}

	public function setProviders($providers)
	{
		$this->providers = $providers;
	}

	public function getProviders()
	{
		return $this->providers;
	}

	public function addProvider($provider)
	{
		$this->providers[] = $provider;
	}

}
