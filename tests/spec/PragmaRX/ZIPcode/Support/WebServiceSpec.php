<?php

namespace spec\PragmaRX\ZIPcode\Support;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Data;

class WebServiceSpec extends ObjectBehavior
{
	private $data;

	public function let()
	{
		$this->data = new Data;

		$this->beConstructedWith($this->data->webService);
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
			'timer',
			'result_raw',
		]);
	}
	public function it_can_get_all_properties()
	{
		$this->getName()->shouldBe($this->data->webService['name']);
		$this->getUrl()->shouldBe($this->data->webService['url']);
		$this->getQuery()->shouldBe($this->data->webService['query']);
		$this->getZipFormat()->shouldBe($this->data->webService['zip_format']);
		$this->getFields()->shouldBe(array_merge($this->data->webService['fields'], ['zip','web_service','country_id', 'timer', 'result_raw']));
		$this->getMandatoryFields()->shouldBe($this->data->webService['mandatory_fields']);
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
