<?php

namespace PragmaRX\ZIPcode\Support;

use PragmaRX\ZIPcode\Exceptions\PropertyDoesNotExists;

class Result extends BaseClass {

	/**
	 * All public properties.
	 *
	 * @var array
	 */
	private $publicProperties = [];

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
		$this->clearProperties();
		$this->clearErrors();

		if ( ! $this->validate($result, $webService))
		{
			return false;
		}

		foreach($webService->getFields() as $property => $nameInResultSet)
		{
			$nameInResultSet = $nameInResultSet ?: $property;

			$property = is_numeric($property) ? $nameInResultSet : $property;

			$this->publicProperties[$property] = array_get($result, $nameInResultSet)
													?: ( isset($this->publicProperties[$property])
														? $this->publicProperties[$property]
														: null );
		}

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
	 * Clear the list of propperties.
	 *
	 */
	private function clearProperties()
	{
		$this->publicProperties = [];
	}

	/**
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
	 * Check if an result is valid.
	 *
	 * @param $result
	 * @param $webService
	 * @return bool
	 */
	private function validate($result, WebService $webService)
	{
		$valid = 0;
		$missingMandatory = false;

		foreach($webService->getFields() as $field => $originalName)
		{
			if ($originalName)
			{
				if ($webService->getField($field))
				{
					$has = array_get($result, $originalName);

					$valid += $has ? 1 : 0;

					if ( ! $has)
					{
						if ($webService->isMandatory($field))
						{
							$missingMandatory = true;

							$this->addError("Mandatory field '$field' is missing from result.");
						}
						else
						{
							$this->addError("Result field '$field' was not found.");
						}
					}
				}
			}
		}

		if ($valid > 0)
		{
			foreach($webService as $key => $field)
			{
				if (substr($key, 0, 7) == '_check_')
				{
					$field = substr($key, 7);

					if ( ! $valid = $valid && $result[$field] == $webService->getField($key))
					{
						$this->addError("Verification field $key should be '".$webService->getField($key)."' and is '$result[$field]'.");
					};
				}
			}
		}

		if ($valid == 0 || $missingMandatory)
		{
			$this->addError('Result is not valid.');

			return false;
		}

		return true;
	}

}