<?php

namespace PragmaRX\ZIPcode\Support;

use PragmaRX\ZIPcode\Support\WebServices;

class Country {

	/**
	 * The country id (BR, US...)
	 *
	 * @var
	 */
	private $id;

	/**
	 * The length of a zip code on this country.
	 *
	 * @var
	 */
	private $zipLength;

	/**
	 * The list of web services.
	 *
	 * @var array
	 */
	private $webServices;

	/**
	 * Create a country.
	 *
	 * @param array $webServices
	 */
	public function __construct(array $webServices = array())
	{
		$this->webServices = new WebServices($webServices);
	}

	/**
	 * Absorb (import) all country data.
	 *
	 * @param $webServices
	 */
	public function setCountryData($webServices)
	{
		$this->zipLength = $webServices['zip_length'];

		$this->webServices->setWebServices($webServices['web_services']);
	}

	/**
	 * @param mixed $zipLength
	 */
	public function setZipLength($zipLength)
	{
		$this->zipLength = $zipLength;
	}

	/**
	 * Zip length getter.
	 *
	 * @return mixed
	 */
	public function getZipLength()
	{
		return $this->zipLength;
	}

	/**
	 * Get the country id.
	 *
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set the country id.
	 *
	 * @param mixed $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * Get the web services tied to the country.
	 *
	 * @return array
	 */
	public function getWebServices()
	{
		return $this->webServices;
	}

}