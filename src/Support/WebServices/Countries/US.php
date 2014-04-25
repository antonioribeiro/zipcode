<?php

return [

	'zip_length' => 5,

	'web_services' => [

		[
			'name' => 'zippopotam',
			'url' => 'http://api.zippopotam.us',
			'query' => '/US/%zip_code%',
			'zip_format' => '99999',
			'fields' => [
				'zip' => 'post code',
				'state_id' => 'places.0.state abbreviation',
				'state_name' => 'places.0.state',
				'city' => 'places.0.place name',
				'country_id' => 'country abbreviation',
				'country_name' => 'country',
				'longitude' => 'places.0.longitude',
				'latitude' => 'places.0.latitude',
			],
		],

		[
		 'name' => 'elevenbasetwo',
			'url' => 'http://zip.elevenbasetwo.com',
			'query' => '/v2/US/%zip_code%',
			'zip_format' => '99999',
			'fields' => [
				'zip' => 'zip',
				'state_id' => null,
				'state_name' => 'state',
				'city' => 'city',
				'country_id' => 'country',
				'street_kind' => null,
				'street_name' => null,
			],
		],

	],

];
