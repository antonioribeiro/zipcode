<?php
/**
 * Created by PhpStorm.
 * User: AntonioCarlos
 * Date: 20/04/2014
 * Time: 18:20
 */

namespace PragmaRX\ZipCode\Support;

class BaseClass {

	/**
	 * The list of errors.
	 *
	 * @var array
	 */
	protected $errors = [];

	/**
	 * Check if there are errors.
	 *
	 * @return bool
	 */
	public function hasErrors()
	{
		return count($this->errors) > 0;
	}

	/**
	 * Add a list of errors to the current error list.
	 *
	 * @param array $errors
	 */
	public function addErrors(array $errors)
	{
		$this->errors = array_merge($this->errors, $errors);
	}

	/**
	 * Add an error to the list of errors.
	 *
	 * @param $error
	 */
	public function addError($error)
	{
		$this->errors[] = $error;
	}

	/**
	 * Clear the errors array.
	 *
	 */
	public function clearErrors()
	{
		$this->errors = [];
	}

	/**
	 * Errors getter.
	 *
	 * @return mixed
	 */
	public function getErrors()
	{
		return $this->errors;
	}

}
