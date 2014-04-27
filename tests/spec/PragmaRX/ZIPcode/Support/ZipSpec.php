<?php

namespace spec\PragmaRX\ZIPcode\Support;

use PragmaRX\ZIPcode\Support\Country;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ZipSpec extends ObjectBehavior
{

	public function let(Country $country)
	{
		$country->setZipLength(8);

		$this->beConstructedWith($country);
	}

    public function it_is_initializable()
    {
        $this->shouldHaveType('PragmaRX\ZIPcode\Support\Zip');
    }

	public function it_knows_valid_zips($country)
	{
		$country->setZipLength(8);

		$this->validateZip('20.250-030')->shouldBe('20250030');

		$this->validateZip('2.0.2.5.0-0.3.0')->shouldBe('20250030');

		$this->validateZip('20250030')->shouldBe('20250030');
	}

	public function it_know_how_to_clear_a_zip_string()
	{
		$this->clearZip('2.0.2.5.0-0.3.0')->shouldBe('20250030');
	}

	public function it_knows_invalid_zips($country)
	{
		$country->getId()->willReturn('BR');

		$country->getZipLength()->willReturn(5);

		$this->validateZip('1234')->shouldReturn(false);

		$this->validateZip('1.2.3.4')->shouldReturn(false);

		$this->validateZip('1234567')->shouldReturn(false);
	}

	public function it_formats_zip_correctly()
	{
		$this->setCode('20250030');
		$this->format('99999-999')->shouldBe('20250-030');

		$this->setCode('20250030');
		$this->format('99999999')->shouldBe('20250030');

		$this->setCode('99750');
		$this->format('99999999')->shouldBe('99750');

		$this->setCode('99750');
		$this->format('99.999')->shouldBe('99.750');

		$this->setCode('123456');
		$this->format('9.9\9/9-9#9')->shouldBe('1.2\3/4-5#6');

		$this->setCode('A1A1A1');
		$this->format('999 999')->shouldBe('A1A 1A1');
	}

}
