<?php

$country = 'JP';

return [

	'zip_length' => 7,

	'country_id' => $country,

	'country_name' => 'Japan',

	'zip_code_example' => '850-0053',

	'web_services' => [

		[
			'name' => 'Geonames',

			'zip_format' => '999-9999',

			'query_parameters' => [
				'country' => $country,
			],
		],

		[
			'name' => 'Zippopotamus',

			'zip_format' => '999-9999',

			'query_parameters' => [
				'country' => $country,
			],
		],

	],

];
