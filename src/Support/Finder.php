<?php

namespace PragmaRX\ZIPcode\Support;

use PragmaRX\ZIPcode\Exceptions\WebServicesNotFound;

class Finder extends BaseClass implements FinderInterface {

	private $zip_instance;

	private $http;

	public function __construct(HttpInterface $http = null)
	{
		$this->http = ! $http
						? new Http()
						: $http;

		$this->result = new Result();
	}

	public function find($zip, $webService = null)
	{
		$this->getZip()->setCode($zip);

		$webServices = ! $webService
			? $this->getZip()->getCountry()->getWebServices()
			: [ $webService ];

		foreach($webServices as $webService)
		{
			if ($result = $this->searchZipUsingWebService($webService))
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
	 * @internal param $zip
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
	 * @throws Exceptions\WebServicesNotFound
	 * @internal param $url
	 * @internal param $query
	 * @internal param $format
	 * @return array|bool
	 */
	public function gatherInformationFromZip($zip, $webService)
	{
		$url = $this->buildUrl($webService);

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
		}

		return $result;
	}

	/**
	 * @return Zip
	 */
	public function getZip()
	{
		return $this->zip_instance;
	}

	/**
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