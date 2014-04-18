<?php

namespace spec\PragmaRX\Zip\Support;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CountrySpec extends ObjectBehavior
{

	private $countryData = [

		'zip_length' => 8,

		'web_services' => [
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
		],

	];

    public function it_is_initializable()
    {
        $this->shouldHaveType('PragmaRX\Zip\Support\Country');
    }

	public function it_can_set_a_country()
	{
		$this->setId('CA');

		$this->getId()->shouldBe('CA');
	}

	public function it_can_import_country_data()
	{
		$this->absorbCountryData($this->countryData);
	}

	public function it_can_import_zip_length_from_country_data()
	{
		$this->absorbCountryData($this->countryData);

		$this->getZipLength()->shouldBe(8);
	}

	public function it_can_get_webservices_from_imported_data()
	{
		$this->absorbCountryData($this->countryData);

		$this->getWebServices()->shouldHaveType('PragmaRX\Zip\Support\WebServices');
	}

}
