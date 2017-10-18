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

	],

];

