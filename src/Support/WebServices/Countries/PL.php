<?php

$country = 'PL';

return [

	'zip_length' => 5,

	'country_id' => $country,

	'country_name' => 'Poland',

	'zip_code_example' => '34-100',

	'web_services' => [

		[
			'name' => 'Geonames',

			'zip_format' => '99-999',

			'query_parameters' => [
				'country' => $country,
			],
		],

		[
			'name' => 'Zippopotamus',

			'zip_format' => '99-999',

			'query_parameters' => [
				'country' => $country,
			],
		],

	],

];

