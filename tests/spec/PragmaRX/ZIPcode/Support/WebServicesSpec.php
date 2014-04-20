<?php

namespace spec\PragmaRX\ZIPcode\Support;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Data;

class WebServicesSpec extends ObjectBehavior
{
	private $data;

	public function let()
	{
		$this->data = new Data;
	}

	public function it_is_initializable()
	{
		$this->shouldHaveType('PragmaRX\ZIPcode\Support\WebServices');
	}

	public function it_can_add_webServices()
	{
		$this->addWebService([]);
		$this->addWebService([]);
		$this->addWebService([]);
		$this->addWebService([]);

		$this->getWebServices()->shouldHaveCount(4);
	}

	public function it_can_set_webServices_from_array()
	{
		// Those should be deleted when setting
		$this->addWebService([]);
		$this->addWebService([]);
		$this->addWebService([]);
		$this->addWebService([]);

		$this->setWebServices([[],[],[],[],[],[],[],[],[],[],]); // adds 10 new webservices

		$this->getWebServices()->shouldHaveCount(10);
	}

	public function it_throws_on_invalid_webservice()
	{
		$this->shouldThrow('PragmaRX\ZIPcode\Exceptions\WebServicesNotFound')->duringGetWebServiceByName('ZZ');
	}

	public function it_can_find_a_webservice_by_name()
	{
		$this->setWebServices($this->data->countryArray['web_services']);

		$this->getWebServiceByName('testwebService')->shouldHaveType('PragmaRX\ZIPcode\Support\WebService');
	}

	public function it_throws_when_web_service_name_is_not_found()
	{
		$this->setWebServices($this->data->countryArray['web_services']);

		$this->shouldThrow('PragmaRX\ZIPcode\Exceptions\WebServicesNotFound')->duringGetWebServiceByName('impossibleWebServiceName');
	}

	public function it_can_set_preferred_web_service()
	{
		$this->setPreferredWebService('name');

		$this->getPreferredWebService()->shouldBe('name');
	}
}
