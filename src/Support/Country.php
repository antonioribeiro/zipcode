<?php

namespace PragmaRX\ZIPcode\Support;

use PragmaRX\ZIPcode\Exceptions\WebServicesNotFound;

use PragmaRX\Support\Filesystem;

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
	 * The country name.
	 *
	 * @var
	 */
	private $name;

	/**
	 * Create a country.
	 *
	 * @param array $webServices
	 * @param \PragmaRX\Support\Filesystem $fileSystem
	 */
	public function __construct(array $webServices = array(), Filesystem $fileSystem = null)
	{
		$this->fileSystem = ! $fileSystem
			? new Filesystem()
			: $fileSystem;

		$this->webServices = new WebServices($webServices);
	}

	/**
	 * Import country data.
	 *
	 * @param $webServices
	 */
	public function setCountryData($webServices)
	{
		$this->zipLength = $webServices['zip_length'];

		$this->name = $webServices['country_name'];

		$this->webServices->setWebServices($webServices['web_services']);
	}

	/**
	 * Set the zip code length.
	 *
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
	 * Get the country name.
	 *
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set the country id.
	 *
	 * @param mixed $id
	 */
	public function setId($id)
	{
		$this->id = $id;

		$this->setCountryData($this->loadWebServices($id));
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

	/**
	 * Load all web services for a country.
	 *
	 * @param $country
	 * @throws WebServicesNotFound
	 * @return mixed
	 */
	private function loadWebServices($country)
	{
		$file = $this->getPath()."/$country.php";

		if ( ! file_exists($file))
		{
			throw new WebServicesNotFound("There are no web services for this country '$country'.", 1);
		}

		try
		{
			return require($file);
		}
		catch(\Exception $e)
		{
			throw new WebServicesNotFound("Error loading web services for country '$country': ".$e->getMessage(), 1);
		}
	}

	/**
	 * Get a list of all countries.
	 *
	 * @return array
	 */
	public function all()
	{
		$all = [];

		$countries = $this->fileSystem->allFiles($this->getPath());

		foreach ($countries as $country)
		{
			$country = require($country->getPathName());

			$all[$country['country_id']] = $country['country_name'];
		}

		return $all;
	}

	/**
	 * The the path where the country file is.
	 *
	 * @return string
	 */
	public function getPath()
	{
		return __DIR__."/WebServices/Countries";
	}
}
