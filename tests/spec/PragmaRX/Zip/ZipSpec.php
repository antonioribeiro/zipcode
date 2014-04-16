<?php

namespace spec\PragmaRX\Zip;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use PragmaRX\Zip\Support\Http;

class ZipSpec extends ObjectBehavior
{
	private $numberOfWebServicesAvailable = 5;

	private $webServiceExample = array(

		'zip_length' => 8,

		'web_services' => array(
			array(
				'name' => 'testwebService',
				'url' => 'testwebService',
				'query' => '',
				'result_type' => 'json',
				'zip_format' => '99999999',
				'_check_resultado' => '1',
				'zip' => 'zip',
				'state_id' => 'uf',
				'state_name' => null,
				'city' => 'cidade',
				'neighborhood' => 'bairro',
				'street_kind' => 'tipo_logradouro',
				'street_name' => 'logradouro',
			),
		),

	);

	private $wrongWebServiceExample = array(
		'name' => 'testwebService',
		'url' => 'testwebService',
		'query' => '',
		'result_type' => 'json',
		'zip_format' => '99999999',
		'_check_resultado' => '1',
	);

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
    	$this->setZip('1')->shouldBe(false);

	    $this->getErrors()->shouldBe(array("Zip code '1' is not valid."));
    }

    public function it_has_webServices()
    {
    	$this->getWebServices()->shouldBeArray();

    	$this->getWebServices()->shouldHaveCount($this->numberOfWebServicesAvailable);
    }

    public function it_can_set_webServices()
    {
    	$this->setWebServices(null);

    	$this->getWebServices()->shouldBeNull();
    }

    public function it_can_add_webService()
    {
    	$this->addWebService(array());

    	$this->getWebServices()->shouldHaveCount(count($this->getWebServices()) + $this->numberOfWebServicesAvailable);
    }

	public function it_can_reach_zip_webServices($http)
	{
		$this->setWebServices($this->webServiceExample);

		$http->ping("testwebService")->willReturn(true);

		$this->checkZipWebServices()->shouldBe(true);
	}

	public function it_can_find_a_zip($http)
	{
		$this->setWebServices($this->webServiceExample);

		$http->consume("testwebService", "json")->willReturn($this->addressExample);

		$this->findZip('20250030')->shouldHaveType('PragmaRX\Zip\Support\Address');
	}

	public function it_can_change_a_country_and_load_webservices()
	{
		$this->setCountry('US');

		$this->getWebServices()->shouldBeArray();
	}

	public function it_throws_on_unavailable_country()
	{
		$this->shouldThrow('PragmaRX\Zip\Exceptions\WebServicesNotFound')->duringSetCountry('ZZ');
	}

	public function it_correctly_get_an_addresses()
	{
		$this->getAddress()->shouldHaveType('PragmaRX\Zip\Support\Address');
	}

	public function it_gets_a_null_zip_after_instantiation()
	{
		$this->getZip()->shouldBe(null);
	}

	public function it_gets_a_correct_zip_after_search($http)
	{
		$this->setWebServices($this->webServiceExample);

		$http->consume("testwebService", "json")->willReturn($this->addressExample);

		$this->findZip('20250030');

		$this->getZip()->shouldBe('20250030');
	}

	public function it_gets_an_empty_list_of_errors_on_success($http)
	{
		$this->setWebServices($this->webServiceExample);

		$http->consume("testwebService", "json")->willReturn($this->addressExample);

		$this->findZip('20250030');

		$this->getErrors()->shouldHaveCount(0);
	}

	public function it_successfully_clear_webservices_list()
	{
		$this->clearWebServicesList();

		$this->getWebServices()->shouldHaveCount(0);
	}

	public function it_gets_a_filled_list_of_errors_on_failed_search($http)
	{
		$this->clearWebServicesList();

		$this->addWebService($this->wrongWebServiceExample);

		$http->consume("testwebService", "json")->willReturn(array()); // returns an empty address

		$this->findZip('20250030');

		$this->getErrors()->shouldHaveCount(1); /// address is invalid error message
	}

	public function it_formats_zip_correctly()
	{
		$this->formatZip('20250030', '99999-999')->shouldBe('20250-030');

		$this->formatZip('20250030', '99999999')->shouldBe('20250030');

		$this->formatZip('99750', '99999999')->shouldBe('99750');

		$this->formatZip('99750', '99.999')->shouldBe('99.750');

		$this->formatZip('123456', '9.9\9/9-9#9')->shouldBe('1.2\3/4-5#6');
	}
}
