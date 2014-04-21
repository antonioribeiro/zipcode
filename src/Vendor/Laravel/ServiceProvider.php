<?php 

/**
 * Part of the ZIPcode package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    ZIPcode
 * @version    0.1.0
 * @author     Antonio Carlos Ribeiro @ PragmaRX
 * @license    BSD License (3-clause)
 * @copyright  (c) 2013, PragmaRX
 * @link       http://pragmarx.com
 */

namespace PragmaRX\ZIPcode\Vendor\Laravel;

use PragmaRX\ZIPcode\ZIPcode;
use PragmaRX\ZIPcode\Support\Http;
use PragmaRX\ZIPcode\Support\Finder;

use PragmaRX\Support\Config;
use PragmaRX\Support\Filesystem;

use PragmaRX\Support\ServiceProvider as PragmaRXServiceProvider;

class ServiceProvider extends PragmaRXServiceProvider {

	/**
	 * The package vendor name (lower case).
	 *
	 * @var string
	 */
	protected $packageVendor = 'pragmarx';

	/**
	 * The package vendor name in caps.
	 *
	 * @var string
	 */
	protected $packageVendorCapitalized = 'PragmaRX';

	/**
	 * The package name (lower case).
	 *
	 * @var string
	 */
	protected $packageName = 'zipcode';

	/**
	 * The package name (capitalized).
	 *
	 * @var string
	 */
	protected $packageNameCapitalized = 'ZIPcode';

	/**
	 * This is the boot method for this ServiceProvider
	 *
	 * @return void
	 */
	public function wakeUp()
	{

	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->preRegister();

		$this->registerZIPcode();
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('select');
	}

	/**
	 * Takes all the components of Select and glues them
	 * together to create Select.
	 *
	 * @return void
	 */
	private function registerZIPcode()
	{
		$this->app['zipcode'] = $this->app->share(function($app)
		{
			$z = new ZIPcode(
				new Finder(
					new Http
				)
			);

			if ($this->getConfig('country_id'))
			{
				$z->setCountry($this->getConfig('country_id'));
			}

			if ($this->getConfig('preferred_web_service'))
			{
				$z->setPreferredWebService($this->getConfig('preferred_web_service'));
			}

			return $z;
		});
	}

	/**
	 * Get the root directory for this ServiceProvider
	 * 
	 * @return string
	 */
	public function getRootDirectory()
	{
		return __DIR__.'/../..';
	}    
}
