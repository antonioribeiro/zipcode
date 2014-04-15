<?php

namespace spec\PragmaRX\Zip;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PragmaRX\Zip\Support\Http;

class ZipSpec extends ObjectBehavior
{
	private $providerExample = array(array(
		'url' => 'testprovider',
		'query' => '',
		'_check_resultado' => '1',
		'zip' => 'zip',
		'state' => 'uf',
		'city' => 'cidade',
		'neighborhood' => 'bairro',
		'street_kind' => 'tipo_logradouro',
		'street_name' => 'logradouro',
	));

	private $addressExample = array(
		'resultado' => '1',
		'zip' => '20250030',
		'uf' => 'RJ',
		'cidade' => 'Rio de Janeiro',
		'bairro' => 'Estácio',
		'tipo_logradouro' => 'Rua',
		'logradouro' => 'Professor Quintino do Vale',
	);

	public function let(Http $http)
	{
		$this->beConstructedWith($http);
	}

    public function it_is_initializable()
    {
        $this->shouldHaveType('PragmaRX\Zip\Zip');
    }

    public function it_knows_valid_zips()
    {
    	$this->validateZip('20.250-030')->shouldBe(true);
    	$this->validateZip('2.0.2.5.0-0.3.0')->shouldBe(true);
    	$this->validateZip('cep:20250030')->shouldBe(true);
    }

	public function it_know_how_to_clear_a_zip_string()
	{
		$this->clearZip('2.0.2.5.0-0.3.0')->shouldBe('20250030');
	}

    public function it_knows_invalid_zips()
    {
    	$this->validateZip('2')->shouldBe(false);
    	$this->validateZip('a')->shouldBe(false);
    	$this->validateZip('2025003a')->shouldBe(false);
    }

    public function it_doesnt_accept_wrong_zips()
    {
    	$this->shouldThrow('\PragmaRX\Zip\Exceptions\InvalidZip')->duringSetZip('1');
    }

    public function it_has_providers()
    {
    	$this->getProviders()->shouldBeArray();

    	$this->getProviders()->shouldHaveCount(1);
    }

    public function it_can_set_providers()
    {
    	$this->setProviders(null);

    	$this->getProviders()->shouldBeNull();
    }

    public function it_can_add_provider()
    {
    	$this->addProvider(array());

    	$this->getProviders()->shouldHaveCount(count($this->getProviders()) + 1);
    }

	public function it_can_reach_zip_providers($http)
	{
		$this->setProviders($this->providerExample);

		$http->ping("testprovider")->willReturn(true);

		$this->checkZipProviders()->shouldBe(true);
	}

	public function it_can_find_a_zip($http)
	{
		$this->setProviders($this->providerExample);

		$http->consume("testprovider?")->willReturn($this->addressExample);

		$this->findZip('20250030')->shouldHaveType('PragmaRX\Zip\Support\Address');
	}

}
