<?php

namespace PragmaRX\Zip\Support;

class Result {

	public function __construct($address = null, $fields = null)
	{
		if ($address)
		{
			$this->parse($address, $fields);
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
		$this->dropProperties();

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

	private function dropProperties()
	{
		foreach(get_object_vars($this) as $name => $property)
		{
			unset($this->{$name});
		}
	}
}