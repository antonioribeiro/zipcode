<?php

namespace PragmaRX\Zip\Support;

class Result {

	public function __construct($address = null)
	{
		if ($address)
		{
			$this->parse($address);
		}
	}

	/**
	 * Parse an array of fields to result properties.
	 *
	 * @param array $address
	 * @param $fields
	 * @return $this
	 */
	public function parse(array $address, $fields)
	{
		if ($address instanceof Address)
		{
			$address = $address->toArray();
		}

		foreach($fields as $field => $relation)
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
		return (array) $this;
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