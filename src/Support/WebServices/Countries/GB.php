<?php

$country = 'GB';

return [

	'zip_length' => 0,

	'country_id' => $country,

	'country_name' => 'Great Britain',

	'zip_code_example' => 'L23YL',

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

	],

];

