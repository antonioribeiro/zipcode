<?php

return [

	'zip_length' => 5,

	'web_services' => [

		[
			'name' => 'zippopotam',
			'url' => 'http://api.zippopotam.us',
			'query' => '/JP/%s',
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

	],

];
