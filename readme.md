# ZIPcode

[![Latest Stable Version](https://poser.pugx.org/pragmarx/zipcode/v/stable.png)](https://packagist.org/packages/pragmarx/zipcode) [![License](https://poser.pugx.org/pragmarx/zipcode/license.png)](https://packagist.org/packages/pragmarx/zipcode)

## A Laravel WorldWide ZIP code searcher

You can use it in Laravel:

    ZIPcode::setCountry('US');

    return Response::make(
        ZIPcode::find('10006')
    );

Or outside it:

    $z = new PragmaRX\ZIPcode\ZIPcode;

    return $z->find('20250030')->toArray();

It automatically renders a JSON if you try to access it as string, but you still can:

    $result = ZIPcode::find('10006');

    $json = $result->toJson();
    $array = $result->toArray();

Select your preferred web service:

    ZIPcode::setPreferredWebService('Zippopotamus');

Get a web service by name, change things on it and find an address/city with it:

    $webService = ZIPcode::getWebServiceByName('Zippopotamus');

    $webSerivice->setUrl('http://api.zippopotam.ca');

    return ZIPcode::find('20250030', $webService);

Create a new web service and add it to the list:

    $webService = new PragmaRX\ZIPcode\Support\WebService;

    $webSerivice->setUrl('http://api.zippopotam.ca');
    $webSerivice->setQuery('/%country%/%zip_code%');

    ZIPcode::addWebService($webService);

Change the user agent Guzzle will use to access the web service:

    ZIPcode::setUserAgent('Googlebot/2.1 (+http://www.google.com/bot.html)');

How much time it took to find a zip?:

    $result = ZIPcode::find('0200');

    echo $result->getTimer();

Get a list of all available countries:

    $array = ZIPcode::getAvailableCountries();

Dynamically change query parameters, so if you have a [Geonames](http://www.geonames.org/) login, you can set it by doing:

    ZIPcode::setQueryParameter('geonames_api_login', 'yourusername');

## Web Services

This package uses web services all around the world to provide addresses and cities information. There are at least 2 web services available to all countries (6 for Brazil), if ZIPcode cannot access one or doesn't find a zip on it, it automatically falls back to the others. If you know of any other web services available that could be better than those, please create an issue or PR with it.

## Result

This is an example of what you get when you search a Zip with it:

```
{
   country_id:"CH",
   country_name:"Switzerland",
   zip:"1005",
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
## Laravel Form Example

This is an unconventionally hacked Laravel router which renders a form to query zips on a selected country:

    Route::any('zipcode', function() {

        echo
            Form::open(array('url' => 'zipcode')) .
            Form::select('country', ZIPcode::getAvailableCountries(), Input::get('country')) .
            Form::text('zipcode', Input::get('zipcode')) .
            Form::submit('go!') .
            Form::close();

        if (Input::get('country'))
        {
            ZIPcode::setCountry(Input::get('country'));

            ZIPcode::setQueryParameter('geonames_api_login', 'demo');

            echo '<pre>';
            var_dump(ZIPcode::find(Input::get('zipcode'))->toArray());
            echo '</pre>';
        }

    });

## Requirements

- Laravel 4.1+
- PHP 5.4+

## Installing

Require the `zipcode` package by **executing** the following command in your command line:

    composer require "pragmarx/zipcode" "1.*"

Add the service provider to your app/config/app.php:

    'PragmaRX\ZIPcode\Vendor\Laravel\ServiceProvider',

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

## About Geonames

This is a really nice service and you should use it as your first option, but for it to be free (for 30,000 credits/day) you have to [create an user account](http://www.geonames.org/login) **and** [enable the free webservices](http://www.geonames.org/manageaccount).

## Author

[Antonio Carlos Ribeiro](http://twitter.com/iantonioribeiro)

## License

ZIPcode is licensed under the BSD 3-Clause License - see the `LICENSE` file for details

## Contributing

Pull requests and issues are more than welcome.
