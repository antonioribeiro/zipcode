<?php

namespace PragmaRX\ZIPcode\Support;

use ArrayIterator;
use Countable;
use ArrayAccess;
use Traversable;
use IteratorAggregate;

use PragmaRX\ZIPcode\Exceptions\PropertyDoesNotExists;

class Result extends BaseClass implements ArrayAccess, IteratorAggregate, Countable {

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

	public function __toString()
	{
		return $this->toJson();
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Retrieve an external iterator
	 * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
	 * @return Traversable An instance of an object implementing <b>Iterator</b> or
	 * <b>Traversable</b>
	 */
	public function getIterator()
	{
		return new ArrayIterator($this->publicProperties);
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Whether a offset exists
	 * @link http://php.net/manual/en/arrayaccess.offsetexists.php
	 * @param mixed $offset <p>
	 * An offset to check for.
	 * </p>
	 * @return boolean true on success or false on failure.
	 * </p>
	 * <p>
	 * The return value will be casted to boolean if non-boolean was returned.
	 */
	public function offsetExists($offset)
	{
		return isset($this->publicProperties[$offset]);
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Offset to retrieve
	 * @link http://php.net/manual/en/arrayaccess.offsetget.php
	 * @param mixed $offset <p>
	 * The offset to retrieve.
	 * </p>
	 * @return mixed Can return all value types.
	 */
	public function offsetGet($offset)
	{
		return $this->publicProperties[$offset];
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Offset to set
	 * @link http://php.net/manual/en/arrayaccess.offsetset.php
	 * @param mixed $offset <p>
	 * The offset to assign the value to.
	 * </p>
	 * @param mixed $value <p>
	 * The value to set.
	 * </p>
	 * @return void
	 */
	public function offsetSet($offset, $value)
	{
		if (is_null($offset))
		{
			$this->publicProperties[] = $value;
		} else {
			$this->publicProperties[$offset] = $value;
		}
	}

	/**
	 * (PHP 5 &gt;= 5.0.0)<br/>
	 * Offset to unset
	 * @link http://php.net/manual/en/arrayaccess.offsetunset.php
	 * @param mixed $offset <p>
	 * The offset to unset.
	 * </p>
	 * @return void
	 */
	public function offsetUnset($offset)
	{
		unset($this->publicProperties[$offset]);
	}

	/**
	 * (PHP 5 &gt;= 5.1.0)<br/>
	 * Count elements of an object
	 * @link http://php.net/manual/en/countable.count.php
	 * @return int The custom count as an integer.
	 * </p>
	 * <p>
	 * The return value is cast to an integer.
	 */
	public function count()
	{
		return count($this->publicProperties);
	}

	/**
	 * Get a public property as object property.
	 *
	 * @param $name
	 * @return null
	 */
	public function __get($name)
	{
		if ( ! isset($this->publicProperties[$name]))
		{
			return null;
		}

		return $this->publicProperties[$name];
	}
}
