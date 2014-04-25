<?php

namespace PragmaRX\ZIPcode\Support;


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
	 * The mandatory fields.
	 *
	 * @var
	 */
	private $mandatoryFields;

	private $queryParameters;

	/**
	 * The fields that will always be present.
	 *
	 * @var array
	 */
	private $fixedFields = [
		'zip',
		'web_service',
		'country_id',
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

		$this->mandatoryFields = isset($webService['mandatory_fields']) ? $webService['mandatory_fields'] : [];

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
	 * @return mixed
	 */
	public function setQuery($query)
	{
		$this->query = $query;
	}

	/**
	 * Query parameter getter.
	 *
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
	 * Check if a field is mandatory.
	 *
	 * @param $field
	 * @return bool
	 */
	public function isMandatory($field)
	{
		return in_array($field, $this->mandatoryFields);
	}

	/**
	 * @return array
	 */
	public function getFixedFields()
	{
		return $this->fixedFields;
	}

	/**
	 * Get the list of mandatory fields.
	 *
	 * @return mixed
	 */
	public function getMandatoryFields()
	{
		return $this->mandatoryFields;
	}

	public function setUrl($url)
	{
		$this->url = $url;
	}

	/**
	 * Build a web service url.
	 *
	 * @param $webService
	 * @return string
	 */
	public function getUrl(Zip $zip)
	{
		return $this->replaceParameters($zip, $this->url.$this->query);
	}

	public function replaceParameters($zip, $url)
	{
		$url = str_replace('%zip_code%', $zip->format($this->getZipFormat()), $url);

		foreach ($this->queryParameters as $name => $value)
		{
			$url = str_replace("%$name%", $value, $url);
		}

		return $url;
	}

	public function getWebServiceInfo($webService)
	{
		$name = $webService['name'];

		$info = $this->loadWebServiceInfo($webService['name']);

		return array_replace_recursive($info, $webService);
	}

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
}