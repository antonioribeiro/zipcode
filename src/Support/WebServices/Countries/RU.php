<?php

$country = 'RU';

return [

	'zip_length' => 6,

	'country_id' => $country,

	'country_name' => 'Russia',

	'zip_code_example' => '109012',

	'web_services' => [

		[
			'name' => 'Geonames',

			'zip_format' => '999999',

			'query_parameters' => [
				'country' => $country,
			],
		],

		[
			'name' => 'Zippopotamus',

			'zip_format' => '999999',

			'query_parameters' => [
				'country' => $country,
			],
		],

		/**
		 * geocoder.ca - http://geocoder.ca/?api=1
		 *
		 * The limits on the free XML port are dynamically assigned.
		 * If our server load goes up, the limit becomes more restrictive and vice versa.
		 * Normally it will be in the range of 500-2000 lookups per day.
		 */
		[
			'name' => 'geocoder',

			'url' => 'http://geocoder.ca',

			'query' => '/?postal=%zip_code%&geoit=XML',

			'zip_format' => '999999',

			'zip' => 'postal',

			'fields' => [
				'state_id' => 'standard.prov',
				'city' => 'standard.city',
				'longitude' => 'latt',
				'latitude' => 'longt',
			],

			'mandatory_fields' => [
				'city',
				'state_id',
			]
		],

	],

];

