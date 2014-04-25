<?php

return [

	'url' => 'http://api.zippopotam.us',

	'query' => '/%country%/%zip_code%',

	'query_parameters' => [
		'country' => 'US',
	],

	'iterate_on' => 'places',

	'fields' => [
		'zip' => 'post code',

		'country_id' => 'country abbreviation',

		'country_name' => 'country',

		'state_id' => 'places.0.state abbreviation',

		'state_name' => 'places.0.state',

		'place' => 'places.0.place name',

		'longitude' => 'places.0.longitude',

		'latitude' => 'places.0.latitude',
	],

];
