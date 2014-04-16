<?php

namespace PragmaRX\Zip\Support;

class Address {

	/**
	 * All available fields.
	 *
	 * @var array
	 */
	public static $fields = array(
		'web_service',
		'zip',
		'state_id',
		'state_name',
		'city',
		'neighborhood',
		'street_kind',
		'street_name',
		'code_in_country',
		'country_id',
		'country_name',
		'area_code',
		'time_zone',
		'longitude',
		'latitude',
	);

	/**
	 * @param null $address
	 */
	public function __construct($address = null)
	{
		if ($address)
		{
			$this->parse($address);
		}
	}

	/**
	 * @param array $address
	 * @return $this
	 */
	public function parse(array $address)
	{
		if ($address instanceof Address)
		{
			$address = $address->toArray();
		}

		foreach(static::$fields as $field)
		{
			$this->{$field} = isset($address[$field]) ? $address[$field] : null;
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return array(
			'zip' => $this->zip,
			'state' => $this->state,
			'city' => $this->city,
			'neighborhood' => $this->neighborhood,
			'street_kind' => $this->street_kind,
			'street_name' => $this->street_name,
		);
	}

	/**
	 * @return string
	 */
	public function toJson()
	{
		return json_encode(
			$this->toArray()
		);
	}
}