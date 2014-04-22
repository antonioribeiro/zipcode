<?php

namespace PragmaRX\ZIPcode\Support;

use PragmaRX\ZIPcode\Exceptions\WebServicesNotFound;
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
		$this->getZip()->setCode($zip);

		$webServices = ! $finderService
						? $this->getZip()->getCountry()->getWebServices()
						: [ $finderService ];

		foreach($webServices as $finderService)
		{
			if ($result = $this->searchZipUsingWebService($finderService))
			{
				return $this->getResult();
			}
		}

		$this->addError('No webServices provided information about this zip code.');

		return new $this->result;
	}

	/**
	 * A general search zip by web service method.
	 *
	 * @param $webService
	 * @throws \PragmaRX\ZIPcode\Exceptions\WebServicesNotFound
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

		if ($result = $this->gatherInformationFromZip($this->getZip(), $webService))
		{
			if ($this->setResult($result, $webService))
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
		$url = $this->buildUrl($webService);

		if ($addTimer)
		{
			$t = (new Timer)->start();
		}

		if ($result = $this->http->consume($url))
		{
			$result['result_raw'] = $result;

			$result['zip'] = ! isset($result['zip']) || empty($result['zip'])
				? $zip->getCode()
				: $result['zip'];

			$result['country_id'] = ! isset($result['country_id']) || empty($result['country_id'])
				? $this->getZip()->getCountry()->getId()
				: $result['country_id'];

			$result['web_service'] = $webService->getName();

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
	 * Build a web service url.
	 *
	 * @param $webService
	 * @return string
	 */
	private function buildUrl($webService)
	{
		return sprintf(
			$webService->getUrl().$webService->getQuery(),
			$this->zip_instance->format($webService->getZipFormat())
		);
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
	 * @return Http|HttpInterface
	 */
	public function getHttp()
	{
		return $this->http;
	}

} 