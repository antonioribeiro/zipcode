<?php

return [

	'zip_length' => 6,

	'web_services' => [

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

			'query' => '/?postal=%s&geoit=XML',

			'zip_format' => '999999',

			'zip' => 'postal',

			'fields' => [
				'state_id' => 'standard.prov',
				'city' => 'standard.city',
				'longitude' => 'latt',
				'latitude' => 'longt',
			],
		],

	],

];

