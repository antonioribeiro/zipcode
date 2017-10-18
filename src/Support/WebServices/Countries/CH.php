<?php

$country = 'CH';

return [

	'zip_length' => 4,

	'country_id' => $country,

	'country_name' => 'Switzerland',

	'zip_code_example' => '1005',

	'web_services' => [

		[
			'name' => 'Geonames',

			'zip_format' => '9999',

			'query_parameters' => [
				'country' => $country,
			],
		],

		[
			'name' => 'Zippopotamus',

			'zip_format' => '9999',

			'query_parameters' => [
				'country' => $country,
			],
		],

	],

];

