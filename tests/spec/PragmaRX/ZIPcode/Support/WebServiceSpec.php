<?php

namespace spec\PragmaRX\ZipCode\Support;

use PragmaRX\ZipCode\Support\Country;
use PragmaRX\ZipCode\Support\Zip;

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
        $this->shouldHaveType('PragmaRX\ZipCode\Support\WebService');
    }

	public function it_has_fixed_fields()
	{
		$this->getFixedFields()->shouldBeArray();

		$this->getFixedFields()->shouldBe($this->data->fixedFields);
	}

	public function it_can_get_all_properties()
	{
		$country = new Country();

		$zip = new Zip($country);

		$this->getName()->shouldBe($this->data->webService['name']);

		$this->getUrl($zip)->shouldBe($this->data->webService['url']);

		$this->getQuery()->shouldBe($this->data->webService['query']);

		$this->getZipFormat()->shouldBe($this->data->webService['zip_format']);

		$this->getFields()->shouldBe(array_merge($this->data->webService['fields'], $this->data->fixedFields));
	}

	public function it_can_get_set_query_parameters()
	{
		$this->setQueryParameter('country', 'BR');

		$this->getQueryParameter('country')->shouldBe('BR');
	}

	public function it_can_get_an_url_with_all_parameters()
	{
		$country = new Country();

		$zip = new Zip($country);

		$zip->setCode('20.250-030');

		$this->setUrl('http://geocode.com');

		$this->setQueryParameter('api_login', 'demo');

		$this->setQueryParameter('country', 'BR');

		$this->setQuery('/?zip=%zip_code%&country=%country%&username=%api_login%');

		$this->getUrl($zip)->shouldBe('http://geocode.com/?zip=20250030&country=BR&username=demo');
	}

	public function it_can_get_a_field_relation()
	{
		$this->getField('neighborhood')->shouldBe('bairro');

		$this->getField('not_available')->shouldBe(null);
	}
}
