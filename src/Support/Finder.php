<?php

namespace PragmaRX\ZipCode\Support;

use PragmaRX\ZipCode\Exceptions\WebServicesNotFound;
use PragmaRX\Support\Timer;

class Finder extends BaseClass implements FinderInterface {

	/**
	 * The zip instance.
	 *
	 * @var
	 */
	private $zip_instance;

	/**
	 * The http layer object.
	 *
	 * @var Http|HttpInterface
	 */
	private $http;

	/**
	 * The query parameters.
	 *
	 * @var array
	 */
	private $queryParameters = [];

	/**
	 * Create a finder.
	 *
	 * @param HttpInterface $http
	 * @param Timer $timer
	 */
	public function __construct(HttpInterface $http = null, Timer $timer = null)
	{
		$this->http = ! $http
						? new Http()
						: $http;

		$this->timer = ! $timer
						? new Timer()
						: $timer;

		$this->result = new Result();
	}

	/**
	 * Find a zip.
	 *
	 * @param $zip
	 * @param null $finderService
	 * @return mixed
	 */
	public function find($zip, $finderService = null)
	{
		if ( ! $this->getZip()->setCode($zip))
		{
			$this->addErrors($this->getZip()->getErrors());

			return $this->makeErrorResult();
		}

		$webServices = ! $finderService
						? $this->getZip()->getCountry()->getWebServices()
						: [ $finderService ];

		foreach ($webServices as $finderService)
		{
			if ($result = $this->searchZipUsingWebService($finderService))
			{
				return $this->getResult();
			}
		}

		$this->addError('No webServices provided information about this zip code.');

		return $this->makeEmptyResult();
	}

	/**
	 * A general search zip by web service method.
	 *
	 * @param $webService
	 * @throws \PragmaRX\ZipCode\Exceptions\WebServicesNotFound
	 * @return bool|mixed
	 */
	public function searchZipUsingWebService($webService)
	{
		if ( ! $webService instanceof WebService)
		{
			if ( ! $webService = $this->getZip()->getCountry()->getWebServices()->getWebServiceByName($webService))
			{
				throw new WebServicesNotFound("No web service found with '$webService'");
			}
		}

		$webService->absorbQueryParameters($this->queryParameters);

		if ($result = $this->gatherInformationFromZip($this->getZip(), $webService))
		{
			// Check and set the result. Success property will be set to true or false.
			//
			$this->setResult($result, $webService);

			if ($this->getResult()->getSuccess())
			{
				return $this->getResult();
			}
		}

		return false;
	}

	/**
	 * Search a zip via HTTP.
	 *
	 * @param $zip
	 * @param $webService
	 * @param bool $addTimer
	 * @return array|bool
	 */
	public function gatherInformationFromZip($zip, $webService, $addTimer = true)
	{
		if ($addTimer)
		{
			$t = (new Timer)->start();
		}

		if ($result = $this->http->consume($webService->getUrl($zip)))
		{
			$result = $this->createFixedResultFields($result, $webService, $zip);

			if ($addTimer)
			{
				$result['timer'] = $t->elapsed();
			}
		}

		return $result;
	}

	/**
	 * Get the current zip instance.
	 *
	 * @return Zip
	 */
	public function getZip()
	{
		return $this->zip_instance;
	}

	/**
	 * Set the zip instance.
	 *
	 * @param Zip $zip
	 */
	public function setZip($zip)
	{
		$this->zip_instance = $zip;
	}

	/**
	 * Result getter.
	 *
	 * @return mixed
	 */
	public function getResult()
	{
		return $this->result;
	}

	/**
	 * Result setter.
	 *
	 * @param mixed $result
	 * @param $webService
	 * @return mixed
	 */
	public function setResult($result, $webService)
	{
		$result = $this->result->parse(
			$result,
			$webService
		);

		$this->addErrors($this->result->getErrors());

		return $result;
	}

	/**
	 * The http object getter.
	 *
	 * @return Http|HttpInterface
	 */
	public function getHttp()
	{
		return $this->http;
	}

	/**
	 * Make an empty result.
	 *
	 * @return mixed
	 */
	private function makeEmptyResult()
	{
		return new $this->result;
	}

	/**
	 * Make a result object with error info.
	 *
	 * @return mixed
	 */
	private function makeErrorResult()
	{
		$result = new $this->result;

		$result->setSuccess(false);

		$result->setErrors($this->getErrors());

		return $result;
	}

	/**
	 * Set a query parameter.
	 *
	 * @param $queryParameter
	 * @param $value
	 */
	public function setQueryParameter($queryParameter, $value)
	{
		$this->queryParameters[$queryParameter] = $value;
	}

	/**
	 * Create the fixed result fields.
	 *
	 * @param $result
	 * @param $webService
	 * @param $zip
	 * @return array
	 */
	private function createFixedResultFields($result, $webService, $zip)
	{
		$result['result_raw'] = $result;

		$result['zip_code'] = !isset($result['zip_code']) || empty($result['zip_code'])
			? $zip->getCode()
			: $result['zip_code'];

		$result['country_id'] = !isset($result['country_id']) || empty($result['country_id'])
			? $this->getZip()->getCountry()->getId()
			: $result['country_id'];

		$result['country_name'] = !isset($result['country_name']) || empty($result['country_name'])
			? $this->getZip()->getCountry()->getName()
			: $result['country_name'];

		$result['service_query_url'] = $webService->getUrl($zip);

		$result['web_service'] = $webService->getName();

		return $result;
	}

}
