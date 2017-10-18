<?php

$country = 'US';

return [

	'zip_length' => 5,

	'country_id' => $country,

	'country_name' => 'United States',

	'zip_code_example' => '10006',

	'web_services' => [

		[
			'name' => 'Geonames',

			'zip_format' => '99999',

			'query_parameters' => [
				'country' => $country,
			],
		],

		[
			'name' => 'Zippopotamus',

			'zip_format' => '99999',

			'query_parameters' => [
				'country' => $country,
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
