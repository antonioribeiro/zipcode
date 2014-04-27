<?php

$country = 'ZA';

return [

	'zip_length' => 4,

	'country_id' => $country,

	'country_name' => 'South Africa',

	'zip_code_example' => '8001',

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

