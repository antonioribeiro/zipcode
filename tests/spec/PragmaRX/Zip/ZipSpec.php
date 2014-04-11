<?php

namespace spec\PragmaRX\Zip;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PragmaRX\Zip\Support\Http;

class ZipSpec extends ObjectBehavior
{
	function let(Http $http)
	{
		$this->beConstructedWith($http);
	}

    function it_is_initializable()
    {
        $this->shouldHaveType('PragmaRX\Zip\Zip');
    }

    function it_knows_valid_zips()
    {
    	$this->validateZip('20.250-030')->shouldBe(true);
    	$this->validateZip('2.0.2.5.0-0.3.0')->shouldBe(true);
    	$this->validateZip('cep:20250030')->shouldBe(true);
    }

    function it_knows_invalid_zips()
    {
    	$this->validateZip('2')->shouldBe(false);
    	$this->validateZip('a')->shouldBe(false);
    	$this->validateZip('2025003a')->shouldBe(false);
    }

    function it_doesnt_accept_wrong_zips()
    {
    	$this->shouldThrow('\PragmaRX\Zip\Exceptions\InvalidZip')->duringSetZip('1');
    }

    function it_has_providers()
    {
    	$this->getProviders()->shouldBeArray();

    	$this->getProviders()->shouldHaveCount(1);
    }

    function it_can_set_providers()
    {
    	$this->setProviders(null);

    	$this->getProviders()->shouldBeNull();
    }

    function it_can_add_provider()
    {
    	$this->addProvider(array());

    	$this->getProviders()->shouldHaveCount(count($this->getProviders()) + 1);
    }

	function it_can_reach_zip_providers($http)
	{
		foreach($this->getProviders() as $provider)
		{
			$http->ping($provider['url'])->willReturn(true);
		}

		$this->checkZipProviders()->shouldBe(true);
	}

}
