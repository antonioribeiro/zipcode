<?php

namespace PragmaRX\Zip;

use PragmaRX\Zip\Support\Http;
use PragmaRX\Zip\Support\Address;
use PragmaRX\Zip\Exceptions\WebServicesNotFound;

class Zip
{
	/**
	 * The HTTP class.
	 *
	 * @var Support\Http
	 */
	private $http;

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

	private $preferredWebService;

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

	private function searchWebServiceByName($name)
	{
		foreach($this->webServices['web_services'] as $key => $service)
		{
			if ($service['name'] == $name)
			{
				return $key;
			}
		}

		return false;
	}

	public function getWebServicesByName($name)
	{
		if ($key = $this->searchWebServiceByName($name))
		{
			return $this->webServices['web_services'][$key];
		}

		return false;
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
				return $this->getAddress();
			}
		}

		$this->addError('There are no webServices available.');

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
	public function gatherInformationFromZip($zip, $webService)
	{
		$url = $this->buildUrl($zip, $webService['url'], $webService['query'], $webService['zip_format']);

		if ($address = $this->http->consume($url, $webService['result_type']))
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
	 * @param $webService
	 * @return mixed
	 */
	public function setAddress($address, $webService)
	{
		if ( ! $address = $this->extractAddressFields($address, $webService))
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
	private function extractAddressFields($address, $webService)
	{
		if ( ! $this->isValidAddress($address, $webService))
		{
			return false;
		}

		$array = array();

		foreach(Address::$fields as $field)
		{
			if (isset($webService[$field]) || isset($address[$field]))
			{
				if (isset($webService[$field]))
				{
					$value = array_get($address, $webService[$field]);
				}
				else
				{
					$value = $address[$field];
				}

				$array[$field] = $value;
			}
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
	private function isValidAddress($address, $webService)
	{
		$valid = true;

		foreach(Address::$fields as $field)
		{
			if (isset($webService[$field]))
			{
				if ( ! $valid = $valid && array_get($address, $webService[$field]))
				{
					$this->addError("Address field '$field' was not found.");
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
			if ($this->setAddress($address, $webService))
			{
				return $this->getAddress();
			}
		}

		return false;
	}

	public function formatZip($zip, $format)
	{
		return format_masked($this->clearZip($zip), $format);
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

		$this->loadWebServices($country);
	}

	private function getZipLength()
	{
		return $this->webServices['zip_length'];
	}

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

	public function clearWebServicesList()
	{
		$this->webServices['web_services'] = array();
	}

	public function setPreferredWebService($service)
	{
		$this->preferredWebService = $service;
	}
}
