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

	/**
	 * The fields that will always be present.
	 *
	 * @var array
	 */
	private $fixedFields = [
		'zip',
		'web_service',
		'country_id',
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
		$this->name = $webService['name'];

		$this->url = $webService['url'];

		$this->query = $webService['query'];

		$this->zipFormat = $webService['zip_format'];

		$this->fields = $webService['fields'];

		$this->mandatoryFields = isset($webService['mandatory_fields']) ? $webService['mandatory_fields'] : [];
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
	 * Query getter.
	 *
	 * @return mixed
	 */
	public function getQuery()
	{
		return $this->query;
	}

	/**
	 * Url getter.
	 *
	 * @return mixed
	 */
	public function getUrl()
	{
		return $this->url;
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

}