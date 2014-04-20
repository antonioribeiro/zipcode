<?php

namespace PragmaRX\ZIPcode\Support;

use PragmaRX\ZIPcode\Exceptions\PropertyDoesNotExists;

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

	/**
	 * Provides dynamic calls.
	 *
	 * @param $name
	 * @param array $arguments
	 * @throws \PragmaRX\ZIPcode\Exceptions\PropertyDoesNotExists
	 * @return mixed
	 */
	public function __call($name, array $arguments)
	{
		if (substr($name, 0, 3) == 'get')
		{
			$property = substr($name, 3);

			$possibleNames = [
				$property,
				snake($property),
				studly($property),
				camel($property),
			];

			foreach ($possibleNames as $name)
			{
				if (isset($this->{$name}))
				{
					return $this->{$name};
				}
			}
		}

		throw new PropertyDoesNotExists("Property '$name' does not exists in Result object.");
	}
}