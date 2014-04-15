<?php

namespace PragmaRX\Zip\Support;

class Address {

	/**
	 * @var
	 */
	public $zip;

	/**
	 * @var
	 */
	public $state;

	/**
	 * @var
	 */
	public $city;

	/**
	 * @var
	 */
	public $neighborhood;

	/**
	 * @var
	 */
	public $street_kind;

	/**
	 * @var
	 */
	public $street_name;

	/**
	 * @param null $address
	 */
	public function __construct($address = null)
	{
		if ($address)
		{
			$this->parse($address);
		}
	}

	/**
	 * @param array $address
	 * @return $this
	 */
	public function parse(array $address)
	{
		if ($address instanceof Address)
		{
			$address = $address->toArray();
		}

		$this->zip = $address['zip'];
		$this->state = $address['state'];
		$this->city = $address['city'];
		$this->neighborhood = $address['neighborhood'];
		$this->street_kind = $address['street_kind'];
		$this->street_name = $address['street_name'];

		return $this;
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return array(
			'zip' => $this->zip,
			'state' => $this->state,
			'city' => $this->city,
			'neighborhood' => $this->neighborhood,
			'street_kind' => $this->street_kind,
			'street_name' => $this->street_name,
		);
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