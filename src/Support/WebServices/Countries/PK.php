<?php

$country = 'PK';

return [

	'zip_length' => 5,

	'country_id' => $country,

	'country_name' => 'Pakistan',

	'zip_code_example' => '44000',

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

