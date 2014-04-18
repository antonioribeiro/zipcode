<?php

namespace PragmaRX\Zip\Support;

use PragmaRX\Zip\Exceptions\WebServicesNotFound;

class Country {

	private $id;

	private $zipLength;

	private $webServices = [];

	private $preferredWebService;

	public function __construct(array $webServices = array())
	{
		if ($webServices)
		{
			$this->absorbCountryData($webServices);
		}
	}

	public function absorbCountryData($webServices)
	{
		$this->zipLength = $webServices['zip_length'];

		$this->setWebServices($webServices['web_services']);
	}

	private function addWebServiceFromArray($webService)
	{
		$webService = new WebService($webService);

		$this->add($webService);
	}

	private function add($webService)
	{
		$this->webServices[] = $webService;
	}

	/**
	 * @return mixed
	 */
	public function getZipLength()
	{
		return $this->zipLength;
	}

	public function clearWebServicesList()
	{
		$this->webServices = [];
	}

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	public function getWebServices()
	{
		$webservices = $this->webServices;

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
	 * @param mixed $preferredWebService
	 */
	public function setPreferredWebService($preferredWebService)
	{
		$this->preferredWebService = $preferredWebService;
	}

	/**
	 * Search a web service by its name.
	 *
	 * @param $name
	 * @throws WebServicesNotFound
	 * @return int|string
	 */
	private function searchWebServiceByName($name)
	{
		foreach($this->webServices as $key => $service)
		{
			if ($service->getName() == $name)
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
		return $this->webServices[$this->searchWebServiceByName($name)];
	}

	/**
	 * Add a web service to the list of webServices.
	 *
	 * @param $webService
	 */
	public function addWebService($webService)
	{
		$this->webServices[] = $webService;
	}

	/**
	 * @param $webServices
	 */
	public function setWebServices($webServices)
	{
		$this->clearWebServicesList();

		foreach ($webServices as $item) {
			$this->addWebServiceFromArray($item);
		}
	}

} 