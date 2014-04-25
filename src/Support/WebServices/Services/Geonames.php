<?php

return [

	'url' => 'http://api.geonames.org/',

	'api_login' => 'demo',

	'query' => 'postalCodeSearch?country=%country%&postalcode=%zip_code%&username=%api_login%',

	'query_parameters' => [
		'api_login' => 'demo',

		'country' => 'US',
	],

	'iterate_on' => 'code',

	'fields' => [
		'zip' => 'postalcode',

		'state_name' => 'adminName1',

		'state_id' => 'adminCode1',

		'city' => 'name',

		'latitude' => 'lat',

		'longitude' => 'lng',

		'department' => 'adminName2',

		'department_id' => 'adminCode2',
		
		'district' => 'adminName3',
	],

];
