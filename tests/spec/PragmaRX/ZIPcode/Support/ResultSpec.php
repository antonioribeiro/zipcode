<?php

namespace spec\PragmaRX\ZIPcode\Support;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResultSpec extends ObjectBehavior
{

	private $resultArray = [
		'zip' => "20250030",
		'state_id' =>  "RJ",
		'state_name' =>  NULL,
		'city' =>  "Rio de Janeiro",
		'neighborhood' =>  "Estácio",
		'street_kind' =>  "Rua",
		'street_name' =>  "Professor Quintino do Vale",
		'missing_field' =>  NULL,
		'web_service' =>  "testwebService",
		'country_id' =>  "BR",
	];

	private $fields = [
		'zip' => 'zip',
		'state_id' => 'uf',
		'state_name' => null,
		'city' => 'cidade',
		'neighborhood' => 'bairro',
		'street_kind' => 'tipo_logradouro',
		'street_name' => 'logradouro',
		'missing_field' => 'whatever',
	];

	public function let()
	{
//		$this->beConstructedWith($country);
	}

    public function it_is_initializable()
    {
        $this->shouldHaveType('PragmaRX\ZIPcode\Support\Result');
    }

	public function it_can_parse_an_address_and_get_properties_from_it()
	{
		$this->parse($this->resultArray, $this->fields);

		$this->getZip()->shouldBe('20250030');
		$this->getCity()->shouldBe('Rio de Janeiro');
		$this->getStreetName()->shouldBe('Professor Quintino do Vale');
	}

	public function it_can_return_array()
	{
		$this->parse($this->resultArray, $this->fields);

		$this->toArray()->shouldBeArray();
	}

	public function it_can_return_json()
	{
		$this->parse($this->resultArray, $this->fields);

		$this->toJson()->shouldBeJson();
	}

}
