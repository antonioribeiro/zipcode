<?php

$country = 'LT';

return [

	'zip_length' => 5,

	'country_id' => $country,

	'country_name' => 'Lithuania',

	'zip_code_example' => '01103',

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

	],

];

