<?php 

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
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

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
		$this->app[$this->packageName] = $this->app->bindShared(
			'PragmaRX\ZipCode\Contracts\ZipCode',
			function($app)
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
			}
		);
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

	/**
	 * Get the full path of the stub config file.
	 *
	 * @return string
	 */
	public function getStubConfigPath()
	{
		$back = DIRECTORY_SEPARATOR.'..';

		return __DIR__.$back.$back.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php';
	}

}
