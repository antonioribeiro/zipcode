<?php

namespace spec\PragmaRX\ZIPcode\Support;

use PragmaRX\ZIPcode\Support\Country;
use PragmaRX\ZIPcode\Support\Http;
use PragmaRX\ZIPcode\Support\Zip;

use PragmaRX\Support\Timer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Data;

class FinderSpec extends ObjectBehavior
{
	private $data;

	public function let(Http $http, Timer $timer)
	{
		$this->data = new Data;

		$this->beConstructedWith($http, $timer);

		$country = new Country();

		$zip = new Zip($country);

		$this->setZip($zip);
	}

    public function it_is_initializable()
    {
        $this->shouldHaveType('PragmaRX\ZIPcode\Support\Finder');
    }

	public function it_can_find_a_zip($http, $timer)
	{
		$this->getZip()->getCountry()->setCountryData($this->data->countryArray);

		$http->consume('testwebService')->willReturn($this->data->dataArray);

		$timer->elapsedRaw()->willReturn('0.0002');

		$this->find('20250030')->shouldHaveType('PragmaRX\ZIPcode\Support\Result');

		$this->find('20250030')->toArray()->shouldBe($this->data->finalResultArray);
	}

	public function it_correctly_get_an_results()
	{
		$this->getResult()->shouldHaveType('PragmaRX\ZIPcode\Support\Result');
	}

	public function it_gets_a_correct_zip_after_search($http)
	{
		$this->getZip()->getCountry()->setCountryData($this->data->countryArray);

		$http->consume('testwebService')->willReturn($this->data->dataArray);

		$this->find('20250030');

		$this->getZip()->getCode()->shouldBe('20250030');
	}

	public function it_gets_an_empty_list_of_errors_on_success($http)
	{
		$this->getZip()->getCountry()->setCountryData($this->data->countryArray);

		$http->consume('testwebService')->willReturn($this->data->dataArray);

		$this->find('20250030');

		$this->getErrors()->shouldBe($this->data->missingFieldError);
	}

	public function it_can_gather_information_from_zip($http)
	{
		$this->getZip()->getCountry()->setCountryData($this->data->countryArray);

		$webService = $this->getZip()->getCountry()->getWebServices()->getWebServiceByName('testwebService');

		$http->consume('testwebService')->willReturn($this->data->dataArray); // returns an empty result

		$this->gatherInformationFromZip('20250-030', $webService)->shouldBe($this->data->dataArrayWithRaw);
	}

	public function it_can_find_zip_by_web_service_name($http)
	{
		$this->getZip()->getCountry()->setCountryData($this->data->countryArray);

		$http->consume('testwebService')->willReturn($this->data->dataArray);

		$this->find('20250030', 'testwebService')->shouldHaveType('PragmaRX\ZIPcode\Support\Result');
	}

	public function it_can_find_zip_on_specific_web_service($http)
	{
		$this->getZip()->getCountry()->setCountryData($this->data->countryArray);

		$http->consume('testwebService')->willReturn($this->data->dataArray);

		$this->find('20250030', $this->getZip()->getCountry()->getWebServices()->getWebServiceByName('testwebService'))->shouldHaveType('PragmaRX\ZIPcode\Support\Result');
	}

	public function it_returns_non_empty_result($http)
	{
		$this->getZip()->getCountry()->setCountryData($this->data->countryArray);

		$http->consume('testwebService')->willReturn($this->data->dataArray);

		$this->find('20250030', $this->getZip()->getCountry()->getWebServices()->getWebServiceByName('testwebService'))->isEmpty()->shouldBe(false);
	}

	public function it_returns_empty_result_on_missing_mandatory_fields($http)
	{
		unset($this->data->dataArray['uf']);

		$this->getZip()->getCountry()->setCountryData($this->data->countryArray);

		$http->consume('testwebService')->willReturn($this->data->dataArray);

		$this->find('20250030', $this->getZip()->getCountry()->getWebServices()->getWebServiceByName('testwebService'))->isEmpty()->shouldBe(true);
	}

}
