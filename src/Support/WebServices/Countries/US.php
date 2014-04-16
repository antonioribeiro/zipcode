<?php

$id = 0;

return array(

	'zip_length' => 5,

	'web_services' => array(

		array(
			'name' => 'zippopotam',
			'url' => 'http://api.zippopotam.us',
			'query' => '/US/%s',
			'result_type' => 'json',
			'zip_format' => '99999',
			'zip' => 'post code',
			'state_id' => 'places.0.state abbreviation',
			'state_name' => 'places.0.state',
			'city' => 'places.0.place name',
			'country_id' => 'country abbreviation',
			'country_name' => 'country',
			'longitude' => 'places.0.longitude',
			'latitude' => 'places.0.latitude',
		),

		array(
		 'name' => 'elevenbasetwo',
			'url' => 'http://zip.elevenbasetwo.com',
			'query' => '/v2/US/%s',
			'result_type' => 'json',
			'zip_format' => '99999',
			'zip' => 'zip',
			'state_id' => null,
			'state_name' => 'state',
			'city' => 'city',
			'country_id' => 'country',
			'street_kind' => null,
			'street_name' => null,
		),

		array(
			'name' => 'geonames',
			'url' => 'http://api.geonames.org',
			'query' => '/postalCodeSearchJSON?formatted=true&postalcode=%s&maxRows=1&username=demo&style=full&country=US',
			'result_type' => 'json',
			'zip_format' => '99999',
			'zip' => 'postalCodes.0.postalCode',
			'state_id' => 'postalCodes.0.adminCode1',
			'state_name' => 'postalCodes.0.adminName1',
			'city' => 'postalCodes.0.placeName',
			'country_id' => 'postalCodes.0.countryCode',
		),

		// Very slow...
		// -----------------------
		// array(
		// 	'name' => 'webservicex',
		// 	'url' => 'http://www.webservicex.net',
		// 	'query' => '/uszip.asmx/GetInfoByZIP?USZip=%s',
		// 	'result_type' => 'xml',
		// 	'zip_format' => '99999',
		// 	'zip' => 'Table.ZIP',
		// 	'state_id' => 'Table.STATE',
		// 	'state_name' => null,
		// 	'area_code' => 'Table.AREA_CODE',
		// 	'city' => 'Table.CITY',
		// 	'time_zone' => 'Table.TIME_ZONE',
		// 	'country_id' => 'country',
		// 	'street_kind' => null,
		// 	'street_name' => null,
		// ),

	),
);
