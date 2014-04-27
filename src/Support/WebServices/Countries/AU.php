<?php

$country = 'AU';

return [

	'zip_length' => 4,

	'country_id' => $country,

	'country_name' => 'Australia',

	'zip_code_example' => '0200',

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
