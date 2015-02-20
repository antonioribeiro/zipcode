<?php

namespace PragmaRX\ZipCode\Contracts;

interface ZipCode
{
	/**
	 * Zip setter & validate zip.
	 *
	 * @param $zip
	 * @return bool
	 */
	public function setZip($zip);

	/**
	 * WebServices setter.
	 *
	 * @param $webServices
	 */
	public function setWebServices($webServices);

	/**
	 * WebServices getter.
	 *
	 * @return array
	 */
	public function getWebServices();

	/**
	 * Find an result by zip.
	 *
	 * @param $zip
	 * @param null $webService
	 * @return bool|void
	 */
	public function find($zip, $webService = null);

	/**
	 * Zip getter.
	 *
	 * @return mixed
	 */
	public function getZip();

	/**
	 * Country getter.
	 *
	 * @return string
	 */
	public function getCountry();

	/**
	 * Country setter.
	 *
	 * @param string $country
	 */
	public function setCountry($country);

	/**
	 * Get the current country zip lenght.
	 *
	 * @return mixed
	 */
	public function getZipLength();

	/**
	 * Preferred web service setter.
	 *
	 * @param $service
	 */
	public function setPreferredWebService($service);

	/**
	 * User agent setter.
	 *
	 * @param $userAgent
	 */
	public function setUserAgent($userAgent);

	/**
	 * User agent getter.
	 *
	 * @return mixed
	 */
	public function getUserAgent();

	/**
	 * Add a web service to the list of web services.
	 *
	 * @param $webService
	 */
	public function addWebService($webService);

	/**
	 * Get a web service by name.
	 *
	 * @param $name
	 * @return mixed
	 */
	public function getWebServiceByName($name);

	/**
	 * Remove all web services from the list of web services.
	 *
	 */
	public function clearWebServicesList();


	/**
	 * Get all available countries.
	 *
	 * @return mixed
	 */
	public function getAvailableCountries();

	/**
	 * Set the query parameter.
	 *
	 * @param $queryParameter
	 * @param $value
	 */
	public function setQueryParameter($queryParameter, $value);

	/**
	 * Zip setter & validate zip.
	 *
	 * @param $zip
	 * @return bool
	 */
	public function clearZip($zip);

	/**
	 * Format a zip for a particular country.
	 *
	 * @param null $zip
	 * @param null $country
	 * @return mixed|null|string
	 */
	public function formatForCountry($zip = null, $country = null);

}
