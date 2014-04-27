<?php

$country = 'PT';

return [

	'zip_length' => 7,

	'country_id' => $country,

	'country_name' => 'Portugal',

	'zip_code_example' => '1100-585',

	'web_services' => [

		[
			'name' => 'Geonames',

			'zip_format' => '9999999',

			'query_parameters' => [
				'country' => $country,
			],
		],

		[
			'name' => 'Zippopotamus',

			'zip_format' => '9999-999',

			'query_parameters' => [
				'country' => $country,
			],
		],

	],

];
