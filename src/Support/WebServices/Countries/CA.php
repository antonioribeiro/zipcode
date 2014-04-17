<?php

return [

	'zip_length' => 6,

	'web_services' => [

		[
			'name' => 'geocoder',
			'url' => 'http://geocoder.ca',
			'query' => '/?postal=%s&geoit=XML',
			'result_type' => 'json',
			'zip_format' => '999999',
			'zip' => 'geodata.post code',
			'state_id' => 'places.0.state abbreviation',
			'state_name' => 'places.0.state',
			'city' => 'places.0.place name',
			'country_id' => 'country abbreviation',
			'country_name' => 'country',
			'longitude' => 'places.0.longitude',
			'latitude' => 'places.0.latitude',
		],

	],

];

