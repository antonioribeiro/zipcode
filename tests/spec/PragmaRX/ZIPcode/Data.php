<?php

class Data {

	public $numberOfWebServicesAvailable = 5;

	public $missingFieldError = ["Result field 'missing_field' was not found."];

	public $countryArray = [
		'zip_length' => 8,

		'web_services' => [
			[
				'name' => 'testwebService',

				'url' => 'testwebService',

				'query' => '',

				'result_type' => 'json',

				'zip_format' => '99999999',

				'_check_resultado' => '1',

				'fields' => [
					'zip' => 'cep',
					'state_id' => 'uf',
					'state_name' => null,
					'city' => 'cidade',
					'neighborhood' => 'bairro',
					'street_kind' => 'tipo_logradouro',
					'street_name' => 'logradouro',
					'missing_field' => 'whatever',
				],

				'mandatory_fields' => [
					'state_id'
				],
			],
		],

	];

	public $wrongWebServiceExample = [
		'name' => 'testwebService',
		'url' => 'testwebService',
		'query' => '',
		'result_type' => 'json',
		'zip_format' => '99999999',
		'_check_resultado' => '1',
	];

	public $dataArray = [
		'resultado' => '1',
		'cep' => '20250030',
		'uf' => 'RJ',
		'cidade' => 'Rio de Janeiro',
		'bairro' => 'Estácio',
		'tipo_logradouro' => 'Rua',
		'logradouro' => 'Professor Quintino do Vale',
		'web_service' => 'testwebService',
		'country_id' => 'BR',
		'zip' => "20250030",
	];

	public $finalResultArray = [
		'zip' => '20250030',
		'state_id' => 'RJ',
		'state_name' => null,
		'city' => 'Rio de Janeiro',
		'neighborhood' => 'Estácio',
		'street_kind' => 'Rua',
		'street_name' => 'Professor Quintino do Vale',
		'missing_field' => null,
		'web_service' => 'testwebService',
		'country_id' => 'BR',
	];

	public $resultArray = [
		'resultado' => '1',
		'cep' => "20250030",
		'uf' =>  "RJ",
		'cidade' =>  "Rio de Janeiro",
		'bairro' =>  "Estácio",
		'tipo_logradouro' =>  "Rua",
		'logradouro' =>  "Professor Quintino do Vale",
		'missing_field' =>  NULL,
	];

	public $webService;

	public function __construct()
	{
		$this->webService = $this->countryArray['web_services'][0];
	}

}
