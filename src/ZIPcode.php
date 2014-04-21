<?php

namespace PragmaRX\ZIPcode;

use PragmaRX\ZIPcode\Support\BaseClass;
use PragmaRX\ZIPcode\Support\Finder;
use PragmaRX\ZIPcode\Support\FinderInterface;
use PragmaRX\ZIPcode\Support\Zip;
use PragmaRX\ZIPcode\Support\Result;
use PragmaRX\ZIPcode\Support\Country;

class ZIPcode extends BaseClass
{
	/**
	 * The HTTP class.
	 *
	 * @var Support\Http
	 */
	private $http;

	/**
	 * The country object, which also holds the related web services.
	 *
	 * @var Support\Country
	 */
	private $country;

	/**
	 * The current zip being searched.
	 *
	 * @var
	 */
	private $zip;

	/**
	 * The current result found.
	 *
	 * @var
	 */
	private $result;

	/**
	 * The class constructor.
	 *
	 * @param FinderInterface $finder
	 */
	public function __construct(FinderInterface $finder = null)
	{
		$this->finder = ! $finder
						? new Finder()
						: $finder;

		$this->result = new Result();

		$this->country = new Country();

		$this->zip = new Zip($this->country);

		$this->finder->setZip($this->zip);

		$this->setCountry('BR');
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

		$this->zip->setCode($zip);
	}

	/**
	 * WebServices setter.
	 *
	 * @param $webServices
	 */
	public function setWebServices($webServices)
	{
		$this->country->getWebServices()->setWebServices($webServices);
	}

	/**
	 * WebServices getter.
	 *
	 * @return array
	 */
	public function getWebServices()
	{
		return $this->country->getWebServices();
	}

	/**
	 * Find an result by zip.
	 *
	 * @param $zip
	 * @param null $webService
	 * @return bool|void
	 */
	public function find($zip, $webService = null)
	{
		$this->result = $this->finder->find($zip, $webService);

		$this->addErrors($this->finder->getErrors());

		return $this->result;
	}

	/**
	 * Zip getter.
	 *
	 * @return mixed
	 */
	public function getZip()
	{
		return $this->zip->getCode();
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
		$this->country->setId($country);
	}

	/**
	 * Get the current country zip lenght.
	 *
	 * @return mixed
	 */
	public function getZipLength()
	{
		return $this->country->getZipLength();
	}

	/**
	 * Preferred web service setter.
	 *
	 * @param $service
	 */
	public function setPreferredWebService($service)
	{
		$this->country->getWebServices()->setPreferredWebService($service);
	}

	/**
	 * User agent setter.
	 *
	 * @param $userAgent
	 */
	public function setUserAgent($userAgent)
	{
		$this->finder->getHttp()->setUserAgent($userAgent);
	}

	/**
	 * User agent getter.
	 *
	 * @return mixed
	 */
	public function getUserAgent()
	{
		return $this->finder->getHttp()->getUserAgent();
	}

	/**
	 * Add a web service to the list of web services.
	 *
	 * @param $webService
	 */
	public function addWebService($webService)
	{
		return $this->country->getWebServices()->addWebService($webService);
	}

	/**
	 * Get a web service by name.
	 *
	 * @param $name
	 * @return mixed
	 */
	public function getWebServiceByName($name)
	{
		return $this->country->getWebServices()->getWebServiceByName($name);
	}

	/**
	 * Remove all web services from the list of web services.
	 *
	 */
	public function clearWebServicesList()
	{
		$this->country->getWebServices()->clearWebServicesList();
	}

}
