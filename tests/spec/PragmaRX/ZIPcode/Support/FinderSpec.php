<?php

namespace spec\PragmaRX\ZipCode\Support;

use PragmaRX\ZipCode\Support\Country;
use PragmaRX\ZipCode\Support\Http;
use PragmaRX\ZipCode\Support\Zip;

use PragmaRX\Support\Timer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Data;
use \RecursiveIteratorIterator;

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
        $this->shouldHaveType('PragmaRX\ZipCode\Support\Finder');
    }

	public function it_can_find_a_zip($http, $timer)
	{
		$this->getZip()->getCountry()->setCountryData($this->data->countryArray);

		$http->consume('testwebService')->willReturn($this->data->dataArray);

		$this->find('20250030')->shouldHaveType('PragmaRX\ZipCode\Support\Result');

		$this->find('20250030')->except(['timer'])->shouldBeEqualArray($this->data->finalResultArray);
	}

	public function it_returns_true_success_finding_a_valid_zipcode($http, $timer)
	{
		$this->getZip()->getCountry()->setCountryData($this->data->countryArray);

		$http->consume('testwebService')->willReturn($this->data->dataArray);

		$this->find('20250030')->getSuccess()->shouldBe(true);
	}

	public function it_correctly_get_an_results()
	{
		$this->getResult()->shouldHaveType('PragmaRX\ZipCode\Support\Result');
	}

	public function it_gets_a_correct_zip_after_search($http, $timer)
	{
		$this->getZip()->getCountry()->setCountryData($this->data->countryArray);

		$http->consume('testwebService')->willReturn($this->data->dataArray);

		$timer->elapsedRaw()->willReturn('0.0002');

		$this->find('20250030');

		$this->getZip()->getCode()->shouldBe('20250030');
	}

	public function it_gets_an_empty_list_of_errors_on_success($http, $timer)
	{
		$this->getZip()->getCountry()->setCountryData($this->data->countryArray);

		$http->consume('testwebService')->willReturn($this->data->dataArray);

		$timer->elapsedRaw()->willReturn('0.0002');

		$this->find('20250030');

		$this->getErrors()->shouldBe([]);
	}

	public function it_can_gather_information_from_zip($http, $timer)
	{
		$zip = $this->getZip();

		$zip->setCode('20250-030');

		$zip->getCountry()->setCountryData($this->data->countryArray);

		$webService = $zip->getCountry()->getWebServices()->getWebServiceByName('testwebService');

		$http->consume('testwebService')->willReturn($this->data->dataArray); // returns an empty result

		$this->gatherInformationFromZip($zip, $webService, false)->shouldBe($this->data->dataArrayWithRaw);
	}

	public function it_can_find_zip_by_web_service_name($http)
	{
		$this->getZip()->getCountry()->setCountryData($this->data->countryArray);

		$http->consume('testwebService')->willReturn($this->data->dataArray);

		$this->find('20250030', 'testwebService')->shouldHaveType('PragmaRX\ZipCode\Support\Result');
	}

	public function it_can_find_zip_on_specific_web_service($http)
	{
		$this->getZip()->getCountry()->setCountryData($this->data->countryArray);

		$http->consume('testwebService')->willReturn($this->data->dataArray);

		$this->find('20250030', $this->getZip()->getCountry()->getWebServices()->getWebServiceByName('testwebService'))->shouldHaveType('PragmaRX\ZipCode\Support\Result');
	}

	public function it_returns_non_empty_result($http)
	{
		$this->getZip()->getCountry()->setCountryData($this->data->countryArray);

		$http->consume('testwebService')->willReturn($this->data->dataArray);

		$this->find('20250030', $this->getZip()->getCountry()->getWebServices()->getWebServiceByName('testwebService'))->isEmpty()->shouldBe(false);
	}

	public function it_returns_false_success_when_nothing_was_found($http)
	{
		$this->getZip()->getCountry()->setCountryData($this->data->countryArray);

		$http->consume('testwebService')->willReturn($this->data->errorArray);

		$this->find('20250030', 'testwebService')->shouldHaveType('PragmaRX\ZipCode\Support\Result');

		$this->find('20250030', 'testwebService')->getSuccess()->shouldBe(false);
	}

	public function getMatchers()
	{
		return [
			'beEqualArray' => function($a, $b) {
				return array_equal($a, $b);
			},
		];
	}
}

function array_equal($a, $b)
{
	$a = one_dimension_array($a);

	$b = one_dimension_array($b);

	return array_diff($a, $b) === array_diff($b, $a);
}

function one_dimension_array($array)
{
	$it =  new \RecursiveIteratorIterator(new \RecursiveArrayIterator($array));

	return iterator_to_array($it, false);
}
