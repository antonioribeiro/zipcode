<?php

namespace spec\PragmaRX\ZIPcode;

use PhpSpec\ObjectBehavior;
use PragmaRX\ZIPcode\Support\Result;
use PragmaRX\ZIPcode\Support\WebService;
use Prophecy\Argument;
use PragmaRX\ZIPcode\Support\Http;

use Data;

class ZIPCodeSpec extends ObjectBehavior
{
	private $webService;

	private $result;

	private $data;

	public function let(Http $http)
	{
		$this->data = new Data;

		$this->webService = new WebService($this->data->webService);

		$this->result = new Result($this->data->finalResultArray, $this->webService);

		$this->beConstructedWith($http);
	}

	public function it_is_initializable()
	{
		$this->shouldHaveType('PragmaRX\ZIPcode\ZIPcode');
	}

	public function it_has_webServices()
	{
		$this->getWebServices()->shouldHaveType('PragmaRX\ZIPcode\Support\WebServices');
	}

	public function it_can_reach_zip_webServices($http)
	{
		$this->getCountry()->setCountryData($this->data->countryArray);

		$http->ping('testwebService')->willReturn(true);

		$this->checkZipWebServices()->shouldBe(true);
	}

	public function it_can_find_a_zip($http)
	{
		$this->getCountry()->setCountryData($this->data->countryArray);

		$http->consume('testwebService')->willReturn($this->data->dataArray);

		$this->find('20250030')->shouldHaveType('PragmaRX\ZIPcode\Support\Result');

		$this->find('20250030')->toArray()->shouldBe($this->data->finalResultArray);
	}

	public function it_can_change_a_country_and_load_webservices()
	{
		$this->setCountry('US');

		$this->getWebServices()->shouldHaveType('PragmaRX\ZIPcode\Support\WebServices');
	}

	public function it_throws_on_unavailable_country()
	{
		$this->shouldThrow('PragmaRX\ZIPcode\Exceptions\WebServicesNotFound')->duringSetCountry('ZZ');
	}

	public function it_correctly_get_an_results()
	{
		$this->getResult()->shouldHaveType('PragmaRX\ZIPcode\Support\Result');
	}

	public function it_gets_an_empty_zip_after_instantiation()
	{
		$this->getZip()->shouldBe("");
	}

	public function it_gets_a_correct_zip_after_search($http)
	{
		$this->getCountry()->setCountryData($this->data->countryArray);

		$http->consume('testwebService')->willReturn($this->data->dataArray);

		$this->find('20250030');

		$this->getZip()->shouldBe('20250030');
	}

	public function it_gets_an_empty_list_of_errors_on_success($http)
	{
		$this->getCountry()->setCountryData($this->data->countryArray);

		$http->consume('testwebService')->willReturn($this->data->dataArray);

		$this->find('20250030');

		$this->getErrors()->shouldBe($this->data->missingFieldError);
	}

	public function it_successfully_clear_webservices_list()
	{
		$this->clearWebServicesList();

		$this->getWebServices()->shouldHaveCount(0);
	}

	public function it_throws_on_invalid_webservice()
	{
		$this->shouldThrow('PragmaRX\ZIPcode\Exceptions\WebServicesNotFound')->duringGetWebServiceByName('ZZ');
	}

	public function it_can_find_a_webservice_by_name()
	{
		$this->getCountry()->setCountryData($this->data->countryArray);

		$this->getWebServiceByName('testwebService')->shouldHaveType('PragmaRX\ZIPcode\Support\WebService');
	}

	public function it_can_set_a_zip()
	{
		$this->setZip('20.123-456');

		$this->getZip()->shouldReturn('20123456');
	}

	public function it_can_gather_information_from_zip($http)
	{
		$this->getCountry()->setCountryData($this->data->countryArray);

		$webService = $this->getWebServiceByName('testwebService');

		$http->consume('testwebService')->willReturn($this->data->dataArray); // returns an empty result

		$this->gatherInformationFromZip('20250-030', $webService)->shouldBe($this->data->dataArray);
	}

	public function it_can_set_a_country()
	{
		$this->setCountry('CA');

		$this->getCountry()->getId()->shouldBe('CA');
	}

	public function it_can_set_a_user_agent($http)
	{
		$http->setUserAgent("CA")->willReturn(null);

		$http->getUserAgent()->willReturn('CA');

		$this->setUserAgent('CA');

		$this->getUserAgent()->shouldBe('CA');
	}

	public function it_can_find_zip_by_web_service_name($http)
	{
		$this->getCountry()->setCountryData($this->data->countryArray);

		$http->consume('testwebService')->willReturn($this->data->dataArray);

		$this->find('20250030', 'testwebService')->shouldHaveType('PragmaRX\ZIPcode\Support\Result');
	}

	public function it_can_find_zip_on_specific_web_service($http)
	{
		$this->getCountry()->setCountryData($this->data->countryArray);

		$http->consume('testwebService')->willReturn($this->data->dataArray);

		$this->find('20250030', $this->getWebServiceByName('testwebService'))->shouldHaveType('PragmaRX\ZIPcode\Support\Result');
	}

	public function it_returns_non_empty_result($http)
	{
		$this->getCountry()->setCountryData($this->data->countryArray);

		$http->consume('testwebService')->willReturn($this->data->dataArray);

		$this->find('20250030', $this->getWebServiceByName('testwebService'))->isEmpty()->shouldBe(false);
	}

	public function it_returns_empty_result_on_missing_mandatory_fields($http)
	{
		unset($this->data->dataArray['uf']);

		$this->getCountry()->setCountryData($this->data->countryArray);

		$http->consume('testwebService')->willReturn($this->data->dataArray);

		$this->find('20250030', $this->getWebServiceByName('testwebService'))->isEmpty()->shouldBe(true);
	}

}