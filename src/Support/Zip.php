<?php

namespace PragmaRX\ZIPcode\Support;

use PragmaRX\ZIPcode\Support\Country;
use PragmaRX\ZIPcode\Exceptions\InvalidZipCode;

class Zip {

	/**
	 * The zip code.
	 *
	 * @var
	 */
	private $code;

	private $country;

	/**
	 * Create a Zip.
	 *
	 * @param Country $country
	 */
	public function __construct(Country $country)
	{
		$this->country = $country;
	}

	/**
	 * Code setter.
	 *
	 * @param $code
	 */
	public function setCode($code)
	{
		$this->validateZip($code);

		$this->code = $code;
	}

	/**
	 * Code getter.
	 *
	 * @return mixed
	 */
	public function getCode()
	{
		return $this->clearZip($this->code);
	}

	/**
	 * Code getter.
	 *
	 * @return mixed
	 */
	public function getOriginalCode()
	{
		return $this->code;
	}

	/**
	 * Zip validator.
	 *
	 * @param $zip
	 * @throws InvalidZipCode
	 * @return bool
	 */
	public function validateZip($zip)
	{
		$zip = $this->clearZip($zip);

		if ($this->country->getZipLength() && strlen($zip) !== $this->country->getZipLength())
		{
			throw new InvalidZipCode;
		}

		return $zip;
	}

	/**
	 * Clear a zip string.
	 *
	 * @param $zip
	 * @return mixed
	 */
	public function clearZip($zip)
	{
		return preg_replace("/[^0-9A-Za-z]/", "", $zip);
	}

	/**
	 * Format a zip string.
	 *
	 * @param $format
	 * @return string
	 */
	public function format($format)
	{
		return format_masked($this->getCode(), $format);
	}

	/**
	 * Country getter.
	 *
	 * @return Country
	 */
	public function getCountry()
	{
		return $this->country;
	}

}