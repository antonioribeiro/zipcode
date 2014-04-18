<?php

namespace PragmaRX\Zip\Support;

use PragmaRX\Zip\Exceptions\WebServicesNotFound;

class WebServices {

	/**
	 * The list of web services.
	 *
	 * @var array
	 */
	private $webServices = [];

	/**
	 * The preferred web service.
	 *
	 * @var
	 */
	private $preferredWebService;

	/**
	 * Create a country.
	 *
	 * @param array $webServices
	 */
	public function __construct(array $webServices = array())
	{
		if ($webServices)
		{
			$this->setWebServices($webServices);
		}
	}

	/**
	 * Add a we bservice from an array.
	 *
	 * @param $webService
	 */
	private function addWebServiceFromArray($webService)
	{
		$webService = new WebService($webService);

		$this->add($webService);
	}

	/**
	 * Add an web service.
	 *
	 * @param $webService
	 */
	private function add($webService)
	{
		$this->webServices[] = $webService;
	}

	/**
	 * Clear the list of web services.
	 *
	 */
	public function clearWebServicesList()
	{
		$this->webServices = [];
	}

	/**
	 * Get the list of web services.
	 *
	 * @return array
	 */
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
	 * Set the preferred web service.
	 *
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
	 * Set the list of web services.
	 *
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