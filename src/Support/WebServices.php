<?php

namespace PragmaRX\ZIPcode\Support;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use PragmaRX\ZIPcode\Exceptions\WebServicesNotFound;
use ArrayAccess;
use Traversable;

class WebServices implements ArrayAccess, IteratorAggregate, Countable {

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
	 * @return mixed
	 */
	public function getPreferredWebService()
	{
		return $this->preferredWebService;
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

	/**
	 * (PHP 5 >= 5.0.0)
	 * Whether a offset exists
	 * @link http://php.net/manual/en/arrayaccess.offsetexists.php
	 * @param mixed $offset
	 * An offset to check for.
	 * @return boolean true on success or false on failure.
	 * The return value will be casted to boolean if non-boolean was returned.
	 */
	public function offsetExists($offset)
	{
		return isset($this->webServices[$offset]);
	}

	/**
	 * (PHP 5 >= 5.0.0)
	 * Offset to retrieve
	 * @link http://php.net/manual/en/arrayaccess.offsetget.php
	 * @param mixed $offset
	 * The offset to retrieve.
	 * @return mixed Can return all value types.
	 */
	public function offsetGet($offset)
	{
		unset($this->webServices[$offset]);
	}

	/**
	 * (PHP 5 >= 5.0.0)
	 * Offset to set
	 * @link http://php.net/manual/en/arrayaccess.offsetset.php
	 * @param mixed $offset
	 * The offset to assign the value to.
	 * @param mixed $value
	 * The value to set.
	 * @return void
	 */
	public function offsetSet($offset, $value)
	{
		if (is_null($offset))
		{
			$this->webServices[] = $value;
		} else {
			$this->webServices[$offset] = $value;
		}
	}

	/**
	 * (PHP 5 >= 5.0.0)
	 * Offset to unset
	 * @link http://php.net/manual/en/arrayaccess.offsetunset.php
	 * @param mixed $offset
	 * The offset to unset.
	 * @return void
	 */
	public function offsetUnset($offset)
	{
		unset($this->webServices[$offset]);
	}

	/**
	 * (PHP 5 >= 5.0.0)
	 * Retrieve an external iterator
	 * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
	 * @return Traversable An instance of an object implementing Iterator or
	 * Traversable
	 */
	public function getIterator()
	{
		return new ArrayIterator($this->webServices);
	}

	/**
	 * (PHP 5 >= 5.1.0)
	 * Count elements of an object
	 * @link http://php.net/manual/en/countable.count.php
	 * @return int The custom count as an integer.
	 * The return value is cast to an integer.
	 */
	public function count()
	{
		return count($this->webServices);
	}

}