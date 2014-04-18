<?php

namespace spec\PragmaRX\Zip\Support;

use PhpSpec\ObjectBehavior;
use PragmaRX\Zip\Support\WebService;
use Prophecy\Argument;
use PragmaRX\Zip\Support\Http;

class WebServicesSpec extends ObjectBehavior
{
	private $webServicesExample = [
		[
			'name' => 'testwebService',

			'url' => 'testwebService',

			'query' => '',

			'result_type' => 'json',

			'zip_format' => '99999999',

			'_check_resultado' => '1',

			'fields' => [
				'zip' => 'zip',
				'state_id' => 'uf',
				'state_name' => null,
				'city' => 'cidade',
				'neighborhood' => 'bairro',
				'street_kind' => 'tipo_logradouro',
				'street_name' => 'logradouro',
			],
		],
	];

	public function it_is_initializable()
	{
		$this->shouldHaveType('PragmaRX\Zip\Support\WebServices');
	}

	public function it_can_add_webService()
	{
		$this->addWebService([]);

		$this->getWebServices()->shouldHaveCount(1);
	}

	public function it_throws_on_invalid_webservice()
	{
		$this->shouldThrow('PragmaRX\Zip\Exceptions\WebServicesNotFound')->duringGetWebServiceByName('ZZ');
	}

	public function it_can_find_a_webservice_by_name()
	{
		$this->setWebServices($this->webServicesExample);

		$this->getWebServiceByName('testwebService')->shouldHaveType('PragmaRX\Zip\Support\WebService');
	}

}
