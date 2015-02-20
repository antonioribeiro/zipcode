<?php 

/**
 * Part of the ZipCode package.
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the 3-clause BSD License.
 *
 * This source file is subject to the 3-clause BSD License that is
 * bundled with this package in the LICENSE file.  It is also available at
 * the following URL: http://www.opensource.org/licenses/BSD-3-Clause
 *
 * @package    ZipCode
 * @version    0.1.0
 * @author     Antonio Carlos Ribeiro @ PragmaRX
 * @license    BSD License (3-clause)
 * @copyright  (c) 2013, PragmaRX
 * @link       http://pragmarx.com
 */

namespace PragmaRX\ZipCode\Vendor\Laravel;

use PragmaRX\ZipCode\ZipCode;
use PragmaRX\ZipCode\Support\Http;
use PragmaRX\ZipCode\Support\Finder;

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
	protected $packageNameCapitalized = 'ZipCode';

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

		$this->registerZipCode();
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
	private function registerZipCode()
	{
		$this->app['zipcode'] = $this->app->share(function($app)
		{
			$z = new ZipCode(
				new Finder(
					new Http
				)
			);

			if ($this->getConfig('country'))
			{
				$z->setCountry($this->getConfig('country'));
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
