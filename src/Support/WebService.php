<?php

namespace PragmaRX\ZIPcode\Support;


use PragmaRX\ZIPcode\Exceptions\WebServicesNotFound;

class WebService {

	/**
	 * The web service name.
	 *
	 * @var
	 */
	private $name;

	/**
	 * The url.
	 *
	 * @var
	 */
	private $url;

	/**
	 * The query string part of the url.
	 *
	 * @var
	 */
	private $query;

	/**
	 * The query string part of the url.
	 *
	 * @var
	 */
	private $iterateOn;

	/**
	 * The zip format allowed by this webservice.
	 *
	 * @var
	 */
	private $zipFormat;

	/**
	 * The fields this web service should return.
	 *
	 * @var
	 */
	private $fields;

	/**
	 * The query parameters.
	 *
	 * @var
	 */
	private $queryParameters = [];

	/**
	 * The fields that should always be present.
	 *
	 * @var array
	 */
	private $fixedFields = [
		'zip_code',
		'web_service',
		'country_id',
		'country_name',
		'service_query_url',
		'timer',
		'result_raw',
	];

	/**
	 * Create a WebService.
	 *
	 * @param null $service
	 */
	public function __construct($service = null)
	{
		if ($service)
		{
			$this->parse($service);
		}
	}

	/**
	 * Parse an array of data belonging to create a web service.
	 *
	 * @param $webService
	 */
	public function parse($webService)
	{
		$webService = $this->getWebServiceInfo($webService);

		$this->name = $webService['name'];

		$this->url = $webService['url'];

		$this->query = $webService['query'];

		$this->zipFormat = $webService['zip_format'];

		$this->iterateOn = isset($webService['iterate_on']) ? $webService['iterate_on'] : [];

		$this->fields = $webService['fields'];

		$this->queryParameters = isset($webService['query_parameters']) ? $webService['query_parameters'] : [];
	}

	/**
	 * Fields getter.
	 *
	 * @return mixed
	 */
	public function getFields()
	{
		return array_merge($this->fields, $this->fixedFields);
	}

	/**
	 * Name getter.
	 *
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Name getter.
	 *
	 * @return mixed
	 */
	public function getIterateOn()
	{
		return $this->iterateOn;
	}

	/**
	 * Query getter.
	 *
	 * @return mixed
	 */
	public function getQuery()
	{
		return $this->query;
	}

	/**
	 * Query setter.
	 *
	 * @param $query
	 * @return mixed
	 */
	public function setQuery($query)
	{
		$this->query = $query;
	}

	/**
	 * Query parameter getter.
	 *
	 * @param $parameterName
	 * @return mixed
	 */
	public function getQueryParameter($parameterName)
	{
		return isset($this->queryParameters[$parameterName])
				? $this->queryParameters[$parameterName]
				: null;
	}

	/**
	 * Query parameter setter.
	 *
	 * @param $queryParameter
	 * @param $value
	 * @return mixed
	 */
	public function setQueryParameter($queryParameter, $value)
	{
		$this->queryParameters[$queryParameter] = $value;
	}

	/**
	 * Zip format getter.
	 *
	 * @return mixed
	 */
	public function getZipFormat()
	{
		return $this->zipFormat;
	}

	/**
	 * Get a field.
	 *
	 * @param $field
	 * @return null
	 */
	public function getField($field)
	{
		return isset($this->fields[$field])
				? $this->fields[$field]
				: null;
	}

	/**
	 * Fixed fields list getter.
	 *
	 * @return array
	 */
	public function getFixedFields()
	{
		return $this->fixedFields;
	}

	/**
	 * Url setter.
	 *
	 * @param $url
	 */
	public function setUrl($url)
	{
		$this->url = $url;
	}

	/**
	 * Build a web service url.
	 *
	 * @param Zip $zip
	 * @return string
	 */
	public function getUrl(Zip $zip)
	{
		return $this->replaceParameters($zip, $this->url.$this->query);
	}

	/**
	 * Replace the list of parameters in the url by its values.
	 *
	 * @param $zip
	 * @param $url
	 * @return mixed
	 */
	public function replaceParameters($zip, $url)
	{
		$url = str_replace('%zip_code%', $zip->format($this->getZipFormat()), $url);

		foreach ($this->queryParameters as $name => $value)
		{
			$url = str_replace("%$name%", $value, $url);
		}

		return $url;
	}

	/**
	 * Get the webservice information data.
	 *
	 * @param $webService
	 * @return array
	 * @throws \PragmaRX\ZIPcode\Exceptions\WebServicesNotFound
	 */
	public function getWebServiceInfo($webService)
	{
		return array_replace_recursive(
			$this->loadWebServiceInfo($webService['name']),
			$webService
		);
	}

	/**
	 * Load the webservice information data from disk.
	 *
	 * @param $name
	 * @throws WebServicesNotFound
	 * @return array
	 */
	public function loadWebServiceInfo($name)
	{
		$file = __DIR__."/WebServices/Services/$name.php";

		if ( ! file_exists($file))
		{
			return [];
		}

		try
		{
			return require($file);
		}
		catch(\Exception $e)
		{
			throw new WebServicesNotFound("Error loading web services for web service '$name': ".$e->getMessage(), 1);
		}
	}

	/**
	 * Import query parameters.
	 *
	 * @param $queryParameters
	 */
	public function absorbQueryParameters($queryParameters)
	{
		foreach ($queryParameters as $key => $parameter)
		{
			$this->setQueryParameter($key, $parameter);
		}
	}
}