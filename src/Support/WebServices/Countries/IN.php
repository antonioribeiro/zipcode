<?php

$country = 'IN';

return [

	'zip_length' => 6,

	'country_id' => $country,

	'country_name' => 'India',

	'zip_code_example' => '110001',

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

