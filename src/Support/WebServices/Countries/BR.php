<?php

return [

	'zip_length' => 8,

	'web_services' => [

		[
			'name' => 'republicavirtual',
			'url' => 'http://republicavirtual.com.br/web_cep.php',
			'query' => '?cep=%s&formato=json',
			'zip_format' => '99999999',
			'_check_resultado' => '1',
			'fields' => [
				'zip' => 'zip',
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
			'query' => 'ws/%s/json/',
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
			'query' => '?CEP=%s',
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
			'query' => '/%s.json',
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
			'query' => '/ws/cep/json/%s/',
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

	],

];
