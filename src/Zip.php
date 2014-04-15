<?php

namespace PragmaRX\Zip;

use PragmaRX\Zip\Exceptions\InvalidZip;
use PragmaRX\Zip\Support\Http;
use PragmaRX\Zip\Support\Address;

class Zip
{
	/**
	 * List of zip providers.
	 *
	 * @var array
	 */
	private $providers = array(
		array(
			'url' => 'http://republicavirtual.com.br/web_cep.php',
			'query' => 'cep=%s&formato=json',
			'_check_resultado' => '1',
			'zip' => 'zip',
			'state' => 'uf',
			'city' => 'cidade',
			'neighborhood' => 'bairro',
			'street_kind' => 'tipo_logradouro',
			'street_name' => 'logradouro',
		),
	);

	private $addressFields = array(
		'zip',
		'state',
		'city',
		'neighborhood',
		'street_kind',
		'street_name',
	);

/**
	 * The HTTP class.
	 *
	 * @var Support\Http
	 */
	private $http;

	/**
	 * The current zip being searched.
	 *
	 * @var
	 */
	private $zip;

	/**
	 * The current address found.
	 *
	 * @var
	 */
	private $address;

	/**
	 * The class constructor.
	 *
	 * @param Http $http
	 */
	public function __construct(Http $http)
	{
		$this->http = $http;

		$this->address = new Address();
	}

	/**
	 * Zip setter & validate zip.
	 *
	 * @param $zip
	 * @throws Exceptions\InvalidZip
	 */
	public function setZip($zip)
	{
		if ( ! $this->validateZip($zip))
		{
			throw new InvalidZip("Zip code '$zip' is not valid.", 1);
		}

		$this->zip = $this->clearZip($zip);
	}

	/**
	 * Zip validator.
	 *
	 * @param $zip
	 * @return bool
	 */
	public function validateZip($zip)
	{
		$zip = $this->clearZip($zip);

		return is_numeric($zip) && strlen($zip) === 8;
	}

	/**
	 * Check if at least one provider is up.
	 *
	 * @return bool
	 */
	public function checkZipProviders()
	{
		foreach($this->getProviders() as $provider)
		{
			if ($this->http->ping($provider['url']))
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Providers setter.
	 *
	 * @param $providers
	 */
	public function setProviders($providers)
	{
		$this->providers = $providers;
	}

	/**
	 * Providers getter.
	 *
	 * @return array
	 */
	public function getProviders()
	{
		return $this->providers;
	}

	/**
	 * Add a provider to the list of providers.
	 *
	 * @param $provider
	 */
	public function addProvider($provider)
	{
		$this->providers[] = $provider;
	}

	/**
	 * Find an address by zip.
	 *
	 * @param $zip
	 * @return bool|void
	 */
	public function findZip($zip)
	{
		$this->setZip($zip);

		foreach($this->getProviders() as $provider)
		{
			if ($address = $this->searchZip($this->getZip(), $provider['url'], $provider['query']))
			{
				if ($this->setAddress($address, $provider))
				{
					return $this->getAddress();
				}
			}
		}

		return false;
	}

	/**
	 * Search a zip via HTTP.
	 *
	 * @param $zip
	 * @param $url
	 * @param $query
	 * @return array|bool
	 */
	private function searchZip($zip, $url, $query)
	{
		$url = $this->buildUrl($zip, $url, $query);

		if ($address = $this->http->consume($url))
		{
			$address['zip'] = $zip;
		}

		return $address;
	}

	/**
	 * Clear a zip string.
	 *
	 * @param $zip
	 * @return mixed
	 */
	public function clearZip($zip)
	{
		return $zip = preg_replace("/[^0-9]/", "", $zip);
	}

	/**
	 * Address getter.
	 *
	 * @return mixed
	 */
	public function getAddress()
	{
		return $this->address;
	}

	/**
	 * Address setter.
	 *
	 * @param mixed $address
	 * @param $provider
	 * @return mixed
	 */
	public function setAddress($address, $provider)
	{
		if ( ! $address = $this->extractAddressFields($address, $provider))
		{
			return false;
		}

		return $this->address->parse($address);
	}

	/**
	 * Zip getter.
	 *
	 * @return mixed
	 */
	public function getZip()
	{
		return $this->zip;
	}

	private function buildUrl($zip, $url, $query)
	{
		return sprintf("$url?$query", $this->clearZip($zip));
	}

	private function extractAddressFields($address, $provider)
	{
		if ( ! $this->isValidAddress($address, $provider))
		{
			return false;
		}

		$array = array();

		foreach($this->addressFields as $field)
		{
			if (isset($provider[$field]))
			{
				$array[$field] = $address[$provider[$field]];
			}
		}

		return $array;
	}

	private function isValidAddress($address, $provider)
	{
		$valid = true;

		foreach($this->addressFields as $field)
		{
			if (isset($provider[$field]))
			{
				$valid = $valid && isset($address[$provider[$field]]);
			}
		}

		if ($valid)
		{
			foreach($provider as $key => $field)
			{
				if (substr($key, 0, 7) == '_check_')
				{
					$field = substr($key, 7);

					$valid = $valid && $address[$field] == $provider[$key];
				}
			}
		}

		return $valid;
	}

}
