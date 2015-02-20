<?php

namespace spec\PragmaRX\ZipCode;

//require __DIR__.'/Data.php'; /// you may need to uncomment this

use PhpSpec\ObjectBehavior;
use PragmaRX\ZipCode\Support\Result;
use PragmaRX\ZipCode\Support\WebService;
use PragmaRX\ZipCode\Support\Country;
use PragmaRX\ZipCode\Support\Zip;
use Prophecy\Argument;
use PragmaRX\ZipCode\Support\Finder;
use PragmaRX\ZipCode\Support\Http;

use Data;

class ZipCodeSpec extends ObjectBehavior
{
	private $webService;

	private $result;

	private $data;

	public function let(Finder $finder)
	{
		$this->data = new Data;

		$this->webService = new WebService($this->data->webService);

		$this->result = new Result($this->data->finalResultArray, $this->webService);

		$this->beConstructedWith($finder);
	}

	public function it_is_initializable()
	{
		$this->shouldHaveType('PragmaRX\ZipCode\ZipCode');
	}

	public function it_has_webServices()
	{
		$this->getWebServices()->shouldHaveType('PragmaRX\ZipCode\Support\WebServices');
	}

	public function it_can_change_a_country_and_load_webservices()
	{
		$this->setCountry('US');

		$this->getWebServices()->shouldHaveType('PragmaRX\ZipCode\Support\WebServices');
	}

	public function it_throws_on_unavailable_country()
	{
		$this->shouldThrow('PragmaRX\ZipCode\Exceptions\WebServicesNotFound')->duringSetCountry('ZZ');
	}

	public function it_gets_an_empty_zip_after_instantiation()
	{
		$this->getZip()->shouldBe("");
	}

	public function it_successfully_clear_webservices_list()
	{
		$this->clearWebServicesList();

		$this->getWebServices()->shouldHaveCount(0);
	}

	public function it_throws_on_invalid_webservice()
	{
		$this->shouldThrow('PragmaRX\ZipCode\Exceptions\WebServicesNotFound')->duringGetWebServiceByName('ZZ');
	}

	public function it_can_find_a_webservice_by_name()
	{
		$this->getCountry()->setCountryData($this->data->countryArray);

		$this->getWebServiceByName('testwebService')->shouldHaveType('PragmaRX\ZipCode\Support\WebService');
	}

	public function it_can_set_a_zip()
	{
		$this->setZip('20.123-456');

		$this->getZip()->shouldReturn('20123456');
	}

	public function it_can_set_a_country()
	{
		$this->setCountry('CA');

		$this->getCountry()->getId()->shouldBe('CA');
	}

	public function it_can_get_a_list_of_countries()
	{
		$this->getAvailableCountries()->shouldBeArray();
	}

}
