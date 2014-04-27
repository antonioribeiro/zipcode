<?php

return [

	'zip_length' => 8,

	'country_id' => 'BR',

	'country_name' => 'Brazil',

	'zip_code_example' => '22290-240',

	'web_services' => [

		[
			'name' => 'RepublicaVirtual',
			'url' => 'http://republicavirtual.com.br/web_cep.php',
			'query' => '?cep=%zip_code%&formato=json',
			'zip_format' => '99999999',
			'_check_resultado' => '1',
			'fields' => [
				'state_id' => 'uf',
				'state_name' => null,
				'city' => 'cidade',
				'neighborhood' => 'bairro',
				'street_kind' => 'tipo_logradouro',
				'street_name' => 'logradouro',
			],
		],

		[
			'name' => 'viacep',
			'url' => 'http://viacep.com.br/',
			'query' => 'ws/%zip_code%/json/',
			'zip_format' => '99999999',
			'fields' => [
				'zip' => 'cep',
				'state_id' => 'uf',
				'state_name' => null,
				'city' => 'localidade',
				'neighborhood' => 'bairro',
				'street_kind' => null,
				'street_name' => 'logradouro',
				'code_in_country' => 'ibge',
			],
		],

		[
			'name' => 'appservidor',
			'url' => 'http://appservidor.com.br/webservice/cep',
			'query' => '?CEP=%zip_code%',
			'zip_format' => '99999999',
			'fields' => [
				'zip' => 'cep',
				'state_id' => 'uf_sigla',
				'state_name' => 'uf_nome',
				'city' => 'cidade',
				'neighborhood' => 'bairro',
				'street_kind' => 'logradouro',
				'street_name' => 'logradouro_nome',
			],
		],

		[
			'name' => 'correiocontrol',
			'url' => 'http://cep.correiocontrol.com.br',
			'query' => '/%zip_code%.json',
			'zip_format' => '99999999',
			'fields' => [
				'zip' => 'cep',
				'state_id' => 'uf',
				'state_name' => null,
				'city' => 'localidade',
				'neighborhood' => 'bairro',
				'street_kind' => null,
				'street_name' => 'logradouro',
			],
		],

		[
			'name' => 'clareslab',
			'url' => 'http://clareslab.com.br',
			'query' => '/ws/cep/json/%zip_code%/',
			'zip_format' => '99999-999',
			'fields' => [
				'zip' => 'cep',
				'state_id' => 'uf',
				'state_name' => null,
				'city' => 'cidade',
				'neighborhood' => 'bairro',
				'street_kind' => null,
				'street_name' => 'endereco',
			],
		],

		[
			'name' => 'Geonames',

			'api_login' => 'demo',

			'query_parameters' => [
				'api_login' => 'demo',
				'country' => 'BR',
			],

			'zip_format' => '99999999',
		],

	],

];
