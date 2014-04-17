<?php

namespace PragmaRX\Zip\Support;


class WebService {

	private $name;

	private $url;

	private $query;

	private $zipFormat;

	private $fields;

	private $fixedFields = [
		'zip',
		'web_service',
		'country_id',
	]

	public function parse($webService)
	{
		$this->name = $webService['name'];

		$this->url = $webService['url'];

		$this->query = $webService['query'];

		$this->zipFormat = $webService['zip_format'];

		$this->fields = $webService['fields'];
	}

	/**
	 * @return mixed
	 */
	public function getFields()
	{
		return array_merge($this->fields, $this->fixedFields);
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @return mixed
	 */
	public function getQuery()
	{
		return $this->query;
	}

	/**
	 * @return mixed
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * @return mixed
	 */
	public function getZipFormat()
	{
		return $this->zipFormat;
	}

} 