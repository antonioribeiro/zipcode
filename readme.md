# ZipCode

[![Latest Stable Version](https://poser.pugx.org/pragmarx/zipcode/v/stable.png)](https://packagist.org/packages/pragmarx/zipcode) [![License](https://poser.pugx.org/pragmarx/zipcode/license.png)](https://packagist.org/packages/pragmarx/zipcode)

## A Laravel WorldWide ZIP code searcher

You can use it in Laravel:

```php
ZipCode::setCountry('US');

return Response::make(
    ZipCode::find('10006')
);
```

Or outside it:

```php
$z = new PragmaRX\ZipCode\ZipCode;

return $z->find('20250030')->toArray();
```

It automatically renders a JSON if you try to access it as string, but you still can:

```php
$result = ZipCode::find('10006');

$json = $result->toJson();
$array = $result->toArray();
```

Select your preferred web service:

```php
ZipCode::setPreferredWebService('Zippopotamus');
```

Get a web service by name, change things on it and find an address/city with it:

```php
$webService = ZipCode::getWebServiceByName('Zippopotamus');

$webSerivice->setUrl('http://api.zippopotam.ca');

return ZipCode::find('20250030', $webService);
```

Create a new web service and add it to the list:

```php
$webService = new PragmaRX\ZipCode\Support\WebService;

$webSerivice->setUrl('http://api.zippopotam.ca');
$webSerivice->setQuery('/%country%/%zip_code%');

ZipCode::addWebService($webService);
```

Change the user agent Guzzle will use to access the web service:

```php
ZipCode::setUserAgent('Googlebot/2.1 (+http://www.google.com/bot.html)');
```

How much time it took to find a zip?:

```php
$result = ZipCode::find('0200');

echo $result->getTimer();
```

Get a list of all available countries:

```php
$array = ZipCode::getAvailableCountries();
```

Dynamically change query parameters, so if you have a [Geonames](http://www.geonames.org/) login, you can set it by doing:

```php
ZipCode::setQueryParameter('geonames_username', 'yourusername');
```

## Web Services

This package uses web services all around the world to provide addresses and cities information. There are at least 2 web services available to all countries (Brazil currently has 6), if ZipCode cannot access one or doesn't find a zip on it, it automatically falls back to the others. If you know of any other web services available that could be better than those, please create an issue or PR with it.

## Result

This is an example of what you get when you search a Zip with it:

```json
{
   country_id:"CH",
   country_name:"Switzerland",
   zip_code:"1005",
   web_service:"Geonames",
   timer:"0.7808",
   service_query_url:"http://api.geonames.org/postalCodeSearch?country=CH&postalcode=1005&username=demo",
   addresses:[
      {
         postal_code:"1005",
         state_name:"Canton de Vaud",
         state_id:"VD",
         city:"Lausanne",
         latitude:"46.51985",
         longitude:"6.64252",
         department:"District de Lausanne",
         department_id:"2225",
         district:"Lausanne"
      }
   ],
   result_raw:{
      totalResultsCount:"1",
      code:{
         postalcode:"1005",
         name:"Lausanne",
         countryCode:"CH",
         lat:"46.51985",
         lng:"6.64252",
         adminCode1:"VD",
         adminName1:"Canton de Vaud",
         adminCode2:"2225",
         adminName2:"District de Lausanne",
         adminCode3:"5586",
         adminName3:"Lausanne"
      }
   },
   success:true
}
```

ZipCode returns a `PragmaRX\ZipCode\Support\Result` object and all properties can be accessed:

* As array
* As string, which will make it return a JSON
* Using camel cased getters:

```php
$result->getWebService();
$result->getCountryName();
```

## Laravel Form Example

This is an unconventionally hacked Laravel router which renders a form to query zips on a selected country:

```php
Route::any('zipcode', function() {

    echo
        Form::open(array('url' => 'zipcode')) .
        Form::select('country', ZipCode::getAvailableCountries(), Input::get('country')) .
        Form::text('zipcode', Input::get('zipcode')) .
        Form::submit('go!') .
        Form::close();

    if (Input::get('country'))
    {
        ZipCode::setCountry(Input::get('country'));

        ZipCode::setQueryParameter('geonames_username', 'demo');

        echo '<pre>';
        var_dump(ZipCode::find(Input::get('zipcode'))->toArray());
        echo '</pre>';
    }

});
```

## Available countries

There are web services tested for the following countries:

* Argentine (AR)
* Australia (AU)
* Brazil (BR)
* Canada (CA)
* Czech Republic (CZ)
* France (FR)
* Germany (DE)
* Great Britain (GB)
* India (IN)
* Italy (IT)
* Japan (JP)
* Lithuania (LT)
* Mexico (MX)
* Pakistan (PK)
* Poland (PL)
* Portugal (PT)
* Russia (RU)
* South Africa (ZA)
* Spain (ES)
* Switzerland (CH)
* Turkey (TR)
* United States (US)

If you need a different one, please ask or just send a pull request with it.

## Requirements

- Laravel 4.1+ or 5+
- PHP 5.4+

## Installing

Install it using [Composer](https://getcomposer.org/doc/01-basic-usage.md):

    composer require "pragmarx/zipcode"

Edit your app/config/app.php and add the Service Provider

    'PragmaRX\ZipCode\Vendor\Laravel\ServiceProvider',

And the Facade

    'ZipCode' => 'PragmaRX\ZipCode\Vendor\Laravel\Facade',

## Usign It

#### Instantiate it directly

```
use PragmaRX\ZipCode\ZipCode;

$zipcode = new ZipCode();

return $zipcode->generateSecretKey()
```

#### In Laravel you can use the IoC Container and the contract

```
$zipcode = app()->make('PragmaRX\ZipCode\Contracts\ZipCode');

return $zipcode->find('20250-030')
```

#### Or Method Injection, in Laravel 5

```
use PragmaRX\ZipCode\Contracts\ZipCode;

class WelcomeController extends Controller {

	public function generateKey(ZipCode $zipcode)
	{
		return $zipcode->find('20250-030');
	}

}
```

## About Geonames

This is a really nice service and you should use it as your first option, but for it to be free (for 30,000 credits/day) you have to [create an user account](http://www.geonames.org/login) **and** [enable the free webservices](http://www.geonames.org/manageaccount). And configure ZipCode to use your username:

```
ZipCode::setCountry('GB');

ZipCode::setQueryParameter('geonames_username', 'yourusername');

ZipCode::find('L23YL');
```

And you can also use config.php to set it:

```
return array(

	...

	'query_parameters' => array(
		'geonames_username' => 'demo',
	)

);
```

## Author

[Antonio Carlos Ribeiro](http://twitter.com/iantonioribeiro)

## License

ZipCode is licensed under the BSD 3-Clause License - see the `LICENSE` file for details

## Contributing

Pull requests and issues are more than welcome.
