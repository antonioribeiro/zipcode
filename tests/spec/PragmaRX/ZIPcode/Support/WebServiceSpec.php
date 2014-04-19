<?php

namespace spec\PragmaRX\ZIPcode\Support;

use PragmaRX\ZIPcode\Support\Country;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WebServiceSpec extends ObjectBehavior
{

	private $webservice = [
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
			'missing_field' => 'whatever',
		],

		'mandatory_fields' => [
			'state_id'
		],
	];

	public function let()
	{
		$this->beConstructedWith($this->webservice);
	}

	public function it_is_initializable()
    {
        $this->shouldHaveType('PragmaRX\ZIPcode\Support\WebService');
    }

	public function it_has_fixed_fields()
	{
		$this->getFixedFields()->shouldBeArray();

		$this->getFixedFields()->shouldBe([
			'zip',
			'web_service',
			'country_id',
		]);
	}
	public function it_can_get_all_properties()
	{
		$this->getName()->shouldBe($this->webservice['name']);
		$this->getUrl()->shouldBe($this->webservice['url']);
		$this->getQuery()->shouldBe($this->webservice['query']);
		$this->getZipFormat()->shouldBe($this->webservice['zip_format']);
		$this->getFields()->shouldBe(array_merge($this->webservice['fields'], ['zip','web_service','country_id']));
		$this->getMandatoryFields()->shouldBe($this->webservice['mandatory_fields']);
	}

	public function it_can_check_if_a_field_is_mandatory()
	{
		$this->isMandatory('state_id')->shouldBe(true);

		$this->isMandatory('not_mandatory')->shouldBe(false);
	}

	public function it_can_get_a_field_relation()
	{
		$this->getField('neighborhood')->shouldBe('bairro');

		$this->getField('not_available')->shouldBe(null);
	}
}
