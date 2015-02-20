<?php

namespace PragmaRX\ZipCode\Vendor\Laravel;

use Illuminate\Support\Facades\Facade as LaravelFacade;

class Facade extends LaravelFacade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'PragmaRX\ZipCode\Contracts\ZipCode';
	}

}
