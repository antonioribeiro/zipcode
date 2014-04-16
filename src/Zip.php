<?php

namespace PragmaRX\Zip;

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
		'BR' => array(
			'zip_length' => 8,
			'providers' => array(
				array(
					'url' => 'http://viacep.com.br/',
					'query' => 'ws/%s/json/',
					'result_type' => 'json',
					'zip_format' => '99999999',
					'zip' => 'cep',
					'state' => 'uf',
					'city' => 'localidade',
					'neighborhood' => 'bairro',
					'street_kind' => null,
					'street_name' => 'logradouro',
					'code_in_country' => 'ibge',
				),

				array(
					'url' => 'http://appservidor.com.br/webservice/cep',
					'query' => '?CEP=%s',
					'result_type' => 'json',
					'zip_format' => '99999999',
					'zip' => 'cep',
					'state' => 'uf_sigla',
					'state_name' => 'uf_nome',
					'city' => 'cidade',
					'neighborhood' => 'bairro',
					'street_kind' => 'logradouro',
					'street_name' => 'logradouro_nome',
				),

				array(
					'url' => 'http://republicavirtual.com.br/web_cep.php',
					'query' => '?cep=%s&formato=json',
					'result_type' => 'json',
					'zip_format' => '99999999',
					'_check_resultado' => '1',
					'zip' => 'zip',
					'state' => 'uf',
					'city' => 'cidade',
					'neighborhood' => 'bairro',
					'street_kind' => 'tipo_logradouro',
					'street_name' => 'logradouro',
				),

				array(
					'url' => 'http://cep.correiocontrol.com.br',
					'query' => '/%s.json',
					'result_type' => 'json',
					'zip_format' => '99999999',
					'zip' => 'cep',
					'state' => 'uf',
					'city' => 'localidade',
					'neighborhood' => 'bairro',
					'street_kind' => null,
					'street_name' => 'logradouro',
				),

				array(
					'url' => 'http://cep.correiocontrol.com.br',
					'query' => '/%s.json',
					'result_type' => 'json',
					'zip_format' => '99999999',
					'zip' => 'cep',
					'state' => 'uf',
					'city' => 'localidade',
					'neighborhood' => 'bairro',
					'street_kind' => null,
					'street_name' => 'logradouro',
				),

				array(
					'url' => 'http://clareslab.com.br',
					'query' => '/ws/cep/json/%s/',
					'result_type' => 'json',
					'zip_format' => '99999-999',
					'zip' => 'cep',
					'state' => 'uf',
					'city' => 'cidade',
					'neighborhood' => 'bairro',
					'street_kind' => null,
					'street_name' => 'endereco',
				),
			),
		),

		'US' => array(
			'zip_length' => 5,
			'providers' => array(
				array(
					'country_code' => 'US',
					'url' => 'http://zip.elevenbasetwo.com',
					'query' => '/v2/US/%s',
					'result_type' => 'json',
					'zip_format' => '99999',
					'zip' => 'zip',
					'state' => 'state',
					'city' => 'city',
					'country' => 'country',
					'street_kind' => null,
					'street_name' => null,
				),
			),
		),
	);

	/**
	 * The HTTP class.
	 *
	 * @var Support\Http
	 */
	private $http;

	/**
	 * Current country.
	 *
	 * @var string
	 */
	private $country = 'BR';

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
	 * @var array
	 */
	private $errors = array();

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
	 * @return bool
	 */
	public function setZip($zip)
	{
		$this->clearErrors();

		if ( ! $this->validateZip($zip))
		{
			$this->addError("Zip code '$zip' is not valid.");

			return false;
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

		return is_numeric($zip) && strlen($zip) === $this->getZipLength();
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

		$this->addError('No zip providers are up.');

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
		return $this->providers[$this->getCountry()]['providers'];
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
		foreach($this->getProviders() as $provider)
		{
			if ($address = $this->searchZipUsingProvider($zip, $provider))
			{
				return $this->getAddress();
			}
		}

		$this->addError('There are no providers available.');

		return false;
	}

	/**
	 * Search a zip via HTTP.
	 *
	 * @param $zip
	 * @param $url
	 * @param $query
	 * @param $resultType
	 * @param $format
	 * @return array|bool
	 */
	private function searchZip($zip, $url, $query, $resultType, $format)
	{
		$url = $this->buildUrl($zip, $url, $query, $format);

		if ($address = $this->http->consume($url, $resultType))
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

	/**
	 * Build a provider url.
	 *
	 * @param $zip
	 * @param $url
	 * @param $query
	 * @param $format
	 * @return string
	 */
	private function buildUrl($zip, $url, $query, $format)
	{
		return sprintf("$url$query", $this->formatZip($this->clearZip($zip), $format));
	}

	/**
	 * Errors getter.
	 *
	 * @return mixed
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * Extract all fields address from a result.
	 *
	 * @param $address
	 * @param $provider
	 * @return array|bool
	 */
	private function extractAddressFields($address, $provider)
	{
		if ( ! $this->isValidAddress($address, $provider))
		{
			return false;
		}

		$array = array();

		foreach(Address::$fields as $field)
		{
			if (isset($provider[$field]))
			{
				$array[$field] = $address[$provider[$field]];
			}
		}

		return $array;
	}

	/**
	 * Check if an address is valid.
	 *
	 * @param $address
	 * @param $provider
	 * @return bool
	 */
	private function isValidAddress($address, $provider)
	{
		$valid = true;

		foreach(Address::$fields as $field)
		{
			if (isset($provider[$field]))
			{
				if ( ! $valid = $valid && isset($address[$provider[$field]]))
				{
					$this->addError("Address field '$field' was not found.");
				}
			}
		}

		if ($valid)
		{
			foreach($provider as $key => $field)
			{
				if (substr($key, 0, 7) == '_check_')
				{
					$field = substr($key, 7);

					if ( ! $valid = $valid && $address[$field] == $provider[$key])
					{
						$this->addError("Verification field $key should be '$provider[$key]' and is '$address[$field]'.");
					};
				}
			}
		}

		if ( ! $valid)
		{
			$this->addError('Address is not valid.');
		}

		return $valid;
	}

	/**
	 * Add an error to the list of errors.
	 *
	 * @param $error
	 */
	private function addError($error)
	{
		$this->errors[] = $error;
	}

	/**
	 * Clear the errors array.
	 *
	 */
	private function clearErrors()
	{
		$this->errors = array();
	}

	/**
	 * A general search zip by provider method.
	 *
	 * @param $zip
	 * @param $provider
	 * @return bool|mixed
	 */
	public function searchZipUsingProvider($zip, $provider)
	{
		$this->setZip($zip);

		if ($address = $this->searchZip($this->getZip(), $provider['url'], $provider['query'], $provider['result_type'], $provider['zip_format']))
		{
			if ($this->setAddress($address, $provider))
			{
				return $this->getAddress();
			}
		}

		return false;
	}

	private function formatZip($zip, $format)
	{
		if ($format == '99999-999')
		{
			$zip = substr($zip, 0, 5).'-'.substr($zip, 5);
		}

		return $zip;
	}

	/**
	 * @return string
	 */
	public function getCountry()
	{
		return $this->country;
	}

	/**
	 * @param string $country
	 */
	public function setCountry($country)
	{
		$this->country = $country;
	}

	private function getZipLength()
	{
		return $this->providers[$this->getCountry()]['zip_length'];
	}

}
