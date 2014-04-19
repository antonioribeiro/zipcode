<?php

namespace PragmaRX\ZIPcode\Support;

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
			$fieldName = is_numeric($field) ? $relation : $field;

			$this->{$fieldName} = isset($address[$fieldName]) ? $address[$fieldName] : null;
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
		foreach(get_object_vars($this) as $property => $value)
		{
			unset($this->{$property});
		}
	}

	public function isEmpty()
	{
		foreach(get_object_vars($this) as $property => $value)
		{
			if (! empty($value))
			{
				return false;
			}
		}

		return true;
	}

}