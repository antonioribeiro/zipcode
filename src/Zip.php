<?php

namespace PragmaRX\Zip;

use PragmaRX\Zip\Exceptions\InvalidZip;
use PragmaRX\Zip\Exceptions\WebServicesNotFound;
use PragmaRX\Zip\Support\Http;
use PragmaRX\Zip\Support\Result;

class Zip
{
	/**
	 * The HTTP class.
	 *
	 * @var Support\Http
	 */
	private $http;

	/**
	 * The list of web services.
	 *
	 * @var
	 */
	private $webServices;

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
	 * The preferred web service.
	 *
	 * @var
	 */
	private $preferredWebService;

	/**
	 * The current address found.
	 *
	 * @var
	 */
	private $address;

	/**
	 * The list of errors.
	 *
	 * @var array
	 */
	private $errors = [];

	/**
	 * The class constructor.
	 *
	 * @param Http $http
	 */
	public function __construct(Http $http)
	{
		$this->http = $http;

		$this->address = new Result();

		$this->setCountry($this->country);
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

		$this->zip = $this->validateZip($zip);
	}

	/**
	 * Zip validator.
	 *
	 * @param $zip
	 * @throws Exceptions\InvalidZip
	 * @return bool
	 */
	public function validateZip($zip)
	{
		$zip = $this->clearZip($zip);

		if ($this->getZipLength() && strlen($zip) !== $this->getZipLength())
		{
			throw new InvalidZip;
		}

		return $zip;
	}

	/**
	 * Check if at least one web service is up.
	 *
	 * @return bool
	 */
	public function checkZipWebServices()
	{
		foreach($this->getWebServices() as $webService)
		{
			if ($this->http->ping($webService['url']))
			{
				return true;
			}
		}

		$this->addError('No zip webServices are up.');

		return false;
	}

	/**
	 * WebServices setter.
	 *
	 * @param $webServices
	 */
	public function setWebServices($webServices)
	{
		$this->webServices = $webServices;
	}

	/**
	 * WebServices getter.
	 *
	 * @return array
	 */
	public function getWebServices()
	{
		$webservices = $this->webServices['web_services'];

		if ($this->preferredWebService)
		{
			if ($key = $this->searchWebServiceByName($this->preferredWebService))
			{
				$service = $webservices[$key];

			    unset($webservices[$key]);

			    array_insert($webservices, $service, 0);
			}
		}

		return $webservices;
	}

	/**
	 * Search a web service by its name.
	 *
	 * @param $name
	 * @return int|string
	 * @throws Exceptions\WebServicesNotFound
	 */
	private function searchWebServiceByName($name)
	{
		foreach($this->webServices['web_services'] as $key => $service)
		{
			if ($service['name'] == $name)
			{
				return $key;
			}
		}

		throw new WebServicesNotFound("Webservice '$name' was not found.");
	}

	/**
	 * Get a web service by its name.
	 *
	 * @param $name
	 * @return mixed
	 */
	public function getWebServiceByName($name)
	{
		return $this->webServices['web_services'][$this->searchWebServiceByName($name)];
	}

	/**
	 * Add a web service to the list of webServices.
	 *
	 * @param $webService
	 */
	public function addWebService($webService)
	{
		$this->webServices['web_services'][] = $webService;
	}

	/**
	 * Find an address by zip.
	 *
	 * @param $zip
	 * @return bool|void
	 */
	public function findZip($zip)
	{
		foreach($this->getWebServices() as $webService)
		{
			if ($address = $this->searchZipUsingWebService($zip, $webService))
			{
				return $this->getResult();
			}
		}

		$this->addError('There are no webServices available.');

		return false;
	}

	/**
	 * Search a zip via HTTP.
	 *
	 * @param $zip
	 * @param $webService
	 * @internal param $url
	 * @internal param $query
	 * @internal param $format
	 * @return array|bool
	 */
	public function gatherInformationFromZip($zip, $webService)
	{
		$url = $this->buildUrl($zip, $webService['url'], $webService['query'], $webService['zip_format']);

		if ($address = $this->http->consume($url))
		{
			$address['zip'] = ! isset($address['zip']) || empty($address['zip'])
								? $zip 
								: $address['zip'];

			$address['country_id'] = ! isset($address['country_id']) || empty($address['country_id']) 
									? $this->getCountry() 
									: $address['country_id'];

			$address['web_service'] = $webService['name'];
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
		return $zip = preg_replace("/[^0-9A-Za-z]/", "", $zip);
	}

	/**
	 * Result getter.
	 *
	 * @return mixed
	 */
	public function getResult()
	{
		return $this->address;
	}

	/**
	 * Result setter.
	 *
	 * @param mixed $address
	 * @param $webService
	 * @return mixed
	 */
	public function setResult($address, $webService)
	{
		if ( ! $address = $this->extractResultFields($address, $webService))
		{
			return false;
		}

		return $this->address->parse($address, $webService['fields']);
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
	 * Build a web service url.
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
	 * @param $webService
	 * @return array|bool
	 */
	private function extractResultFields($address, $webService)
	{
		if ( ! $this->isValidResult($address, $webService))
		{
			return false;
		}

		$array = [];

		foreach($webService['fields'] as $field => $originalName)
		{
			$value = array_get($address, $originalName);

			$array[$field] = $value;
		}

		return $array;
	}

	/**
	 * Check if an address is valid.
	 *
	 * @param $address
	 * @param $webService
	 * @return bool
	 */
	private function isValidResult($address, $webService)
	{
		$valid = true;

		foreach($webService['fields'] as $field => $originalName)
		{
			if (isset($webService[$field]))
			{
				$has = array_get($address, $originalName);
				$valid = $valid && $has;

				if ( ! $has)
				{
					$this->addError("Result field '$field' was not found.");
				}
			}
		}

		if ($valid)
		{
			foreach($webService as $key => $field)
			{
				if (substr($key, 0, 7) == '_check_')
				{
					$field = substr($key, 7);

					if ( ! $valid = $valid && $address[$field] == $webService[$key])
					{
						$this->addError("Verification field $key should be '$webService[$key]' and is '$address[$field]'.");
					};
				}
			}
		}

		if ( ! $valid)
		{
			$this->addError('Result is not valid.');
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
		$this->errors = [];
	}

	/**
	 * A general search zip by web service method.
	 *
	 * @param $zip
	 * @param $webService
	 * @return bool|mixed
	 */
	public function searchZipUsingWebService($zip, $webService)
	{
		$this->setZip($zip);

		if ($address = $this->gatherInformationFromZip($this->getZip(), $webService))
		{
			if ($this->setResult($address, $webService))
			{
				return $this->getResult();
			}
		}

		return false;
	}

	/**
	 * Format a zip string.
	 *
	 * @param $zip
	 * @param $format
	 * @return string
	 */
	public function formatZip($zip, $format)
	{
		return format_masked($this->clearZip($zip), $format);
	}

	/**
	 * Country getter.
	 *
	 * @return string
	 */
	public function getCountry()
	{
		return $this->country;
	}

	/**
	 * Country setter.
	 *
	 * @param string $country
	 */
	public function setCountry($country)
	{
		$this->country = $country;

		$this->loadWebServices($country);
	}

	/**
	 * Get the current country zip lenght.
	 *
	 * @return mixed
	 */
	private function getZipLength()
	{
		return $this->webServices['zip_length'];
	}

	/**
	 * Load all web services for a country.
	 *
	 * @param $country
	 * @throws Exceptions\WebServicesNotFound
	 */
	private function loadWebServices($country)
	{
		$file = __DIR__."/Support/WebServices/Countries/$country.php";

		if ( ! file_exists($file))
		{
			throw new WebServicesNotFound("There are no web services for this country '$country'.", 1);
		}

		try
		{
			$this->setWebServices(require($file));
		}
		catch(\Exception $e)
		{
			throw new WebServicesNotFound("Error loading web services for country country '$country': ".$e->getMessage(), 1);
		}
	}

	/**
	 * Clear the list of webservices.
	 *
	 */
	public function clearWebServicesList()
	{
		$this->webServices['web_services'] = [];
	}

	/**
	 * Preferred web service setter.
	 *
	 * @param $service
	 */
	public function setPreferredWebService($service)
	{
		$this->preferredWebService = $service;
	}

	/**
	 * User agent setter.
	 *
	 * @param $userAgent
	 */
	public function setUserAgent($userAgent)
	{
		$this->http->setUserAgent($userAgent);
	}

	/**
	 * User agent getter.
	 *
	 * @return mixed
	 */
	public function getUserAgent()
	{
		return $this->http->getUserAgent();
	}

}
