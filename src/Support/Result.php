<?php

namespace PragmaRX\ZIPcode\Support;

use PragmaRX\ZIPcode\Exceptions\PropertyDoesNotExists;

class Result extends BaseClass {

	/**
	 * All public properties.
	 *
	 * @var array
	 */
	private $publicProperties = [
		'success' => false
	];

	/**
	 * Create a result.
	 *
	 * @param null $address
	 * @param null $fields
	 */
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
	 * @param array $result
	 * @param WebService $webService
	 * @return bool
	 */
	public function parse(array $result, WebService $webService)
	{
		$this->clearAll();

		$fields = $webService->getFields();

		$fixed = [];

		foreach($fields as $key => $value)
		{
			if (is_numeric($key))
			{
				$fixed[] = $value;

				unset($fields[$key]);
			}
		}

		if (($iterateOn = $webService->getIterateOn()) && isset($result[$webService->getIterateOn()]))
		{
			$places = $result[$iterateOn];

			if (count($places) == count($places, 1))
			{
				$places = [$places];
			}
		}
		else
		{
			$places = [$result];
		}

		foreach ($places as $place)
		{
			$properties = [];

			foreach($fields as $property => $nameInResultSet)
			{
				$nameInResultSet = $nameInResultSet ?: $property;

				$property = is_numeric($property) ? $nameInResultSet : $property;

				$properties[$property] = array_get($place, $nameInResultSet)
											?: ( array_get($result, $nameInResultSet)
													?: ( isset($this->publicProperties[$property])
															? $this->publicProperties[$property]
															: null )
													);
			}

			$this->publicProperties['addresses'][] = $properties;
		}

		foreach($fixed as $key)
		{
			$this->publicProperties[$key] = isset($result[$key])
											? $result[$key]
											: null;
		}

		$this->publicProperties['success'] = $this->validate();

		return true;
	}

	/**
	 * Convert to array.
	 *
	 * @return array
	 */
	public function toArray()
	{
		return $this->publicProperties;
	}

	/**
	 * Get all of the result as array except for a specified array of items.
	 *
	 * @param $keys
	 * @return array
	 */
	public function except($keys)
	{
		return array_diff_key($this->toArray(), array_flip((array) $keys));
	}

	/**
	 * Convert to json.
	 *
	 * @return string
	 */
	public function toJson()
	{
		return json_encode(
			$this->toArray()
		);
	}

	/**
	 * Clear the list of properties.
	 *
	 */
	private function clearProperties()
	{
		$this->publicProperties = [];
	}

	/**
	 * Check if the result is empty.
	 *
	 * @return bool
	 */
	public function isEmpty()
	{
		foreach($this->toArray() as $value)
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
				if (isset($this->publicProperties[$name]))
				{
					return $this->publicProperties[$name];
				}
			}
		}

		throw new PropertyDoesNotExists("Property '$name' does not exists in Result object.");
	}

	/**
	 * Check if this result is valid.
	 *
	 */
	private function validate()
	{
		$propertiesCount = 0;

		foreach ($this->publicProperties['addresses'] as $properties)
		{
			foreach($properties as $value)
			{
				$propertiesCount += is_null($value) ? 0 : 1;
			}
		}

		return $propertiesCount >= 2;
	}

	/**
	 * Set the success property.
	 *
	 * @param $bool
	 */
	public function setSuccess($bool)
	{
		$this->publicProperties['success'] = $bool;
	}

	/**
	 * Set the errors property.
	 *
	 * @param $errors
	 */
	public function setErrors($errors)
	{
		$this->publicProperties['errors'] = $errors;
	}

	/**
	 * Cleanup the result.
	 *
	 */
	private function clearAll()
	{
		$this->clearProperties();

		$this->clearErrors();
	}

}