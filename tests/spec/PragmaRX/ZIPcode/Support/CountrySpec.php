<?php

namespace spec\PragmaRX\ZIPcode\Support;

require __DIR__.'/../Data.php';

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Data;

class CountrySpec extends ObjectBehavior
{
	private $data;

	private $countryCount = 22;

	public function let()
	{
		$this->data = new Data;
	}

	public function it_is_initializable()
    {
        $this->shouldHaveType('PragmaRX\ZIPcode\Support\Country');
    }

	public function it_can_set_a_country()
	{
		$this->setId('CA');

		$this->getId()->shouldBe('CA');
	}

	public function it_can_import_country_data()
	{
		$this->setCountryData($this->data->countryArray);
	}

	public function it_can_import_zip_length_from_country_data()
	{
		$this->setCountryData($this->data->countryArray);

		$this->getZipLength()->shouldBe(8);
	}

	public function it_can_get_web_services_from_imported_data()
	{
		$this->setCountryData($this->data->countryArray);

		$this->getWebServices()->shouldHaveType('PragmaRX\ZIPcode\Support\WebServices');
	}

	public function it_can_get_a_list_of_coutries()
	{
		$this->all()->shouldHaveCount($this->countryCount);
	}
}
