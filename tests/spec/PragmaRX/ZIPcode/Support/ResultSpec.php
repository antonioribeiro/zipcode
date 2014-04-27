<?php

namespace spec\PragmaRX\ZIPcode\Support;

// require __DIR__.'/../Data.php'; /// you may need to uncomment this

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use PragmaRX\ZIPcode\Support\WebService;

use Data;

class ResultSpec extends ObjectBehavior
{
	private $data;

	public function let()
	{
		$this->data = new Data;
	}

	public function it_is_initializable()
    {
        $this->shouldHaveType('PragmaRX\ZIPcode\Support\Result');
    }

	public function it_can_parse_an_address_and_get_properties_from_it()
	{
		$this->parse($this->data->resultArray, new WebService($this->data->webService));

		$this->getZip()->shouldBe('20250030');

		$this->getAddresses()->shouldBe($this->data->address);
	}

	public function it_can_return_array()
	{
		$this->parse($this->data->resultArray, new WebService($this->data->webService));

		$this->toArray()->shouldBeArray();
	}

	public function it_can_return_json()
	{
		$this->parse($this->data->resultArray, new WebService($this->data->webService));

		$this->toJson()->shouldBeJson();
	}

}
