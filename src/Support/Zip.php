<?php

namespace PragmaRX\ZipCode\Support;

use PragmaRX\ZipCode\Exceptions\InvalidZipCode;

class Zip extends BaseClass
{
    /**
     * The zip code.
     *
     * @var
     */
    private $code;

    /**
     * The country.
     *
     * @var Country
     */
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
     * @return bool
     */
    public function setCode($code)
    {
        if (!$this->validateZip($code))
        {
            return false;
        }

        $this->code = $code;

        return true;
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
     * @return bool
     */
    public function validateZip($zip)
    {
        $zip = $this->clearZip($zip);

        if ($this->country->getZipLength() && strlen($zip) !== $this->country->getZipLength())
        {
            $this->addError(
                sprintf(
                    "Wrong zip length: in %s zip length is %s not %s.",
                    $this->country->getId(),
                    $this->country->getZipLength(),
                    strlen($zip)
                )
            );

            return false;
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

    /**
     * Country setter.
     *
     * @param Country $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    public function formatForCountry($zip = null, $country = null)
    {
        $zip = $this->clearZip($zip) ?: $this->getCode();

        $country = $country ?: $this->getCountry();

        if ($country == 'BR')
        {
            return format_masked($zip, '99999-999');
        }

        return $zip;
    }
}
