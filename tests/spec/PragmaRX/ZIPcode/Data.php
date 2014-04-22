<?php

class Data {

	public $webService;

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
					'sub_value' => 'sub.value',
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
		'timer' => '0.0002',
		'zip' => "20250030",
		'sub' => ['value' => 'this is the sub value'],
	];

	public $finalResultArray = [
		'zip' => '20250030',
		'state_id' => 'RJ',
		'state_name' => null,
		'city' => 'Rio de Janeiro',
		'neighborhood' => 'Estácio',
		'street_kind' => 'Rua',
		'street_name' => 'Professor Quintino do Vale',
		'sub_value' => 'this is the sub value',
		'missing_field' => null,
		'web_service' => 'testwebService',
		'country_id' => 'BR',
		'timer' => '0.0002',
	];

	public $resultArray = [
		'resultado' => '1',
		'cep' => "20250030",
		'uf' =>  "RJ",
		'cidade' =>  "Rio de Janeiro",
		'bairro' =>  "Estácio",
		'tipo_logradouro' =>  "Rua",
		'logradouro' =>  "Professor Quintino do Vale",
		'sub' => ['value' => 'this is the sub value'],
		'missing_field' =>  NULL,
	];

	public function __construct()
	{
		$this->webService = $this->countryArray['web_services'][0];

	    $this->dataArrayWithRaw = $this->dataArray;

		$this->dataArrayWithRaw['result_raw'] = $this->dataArray;

		$this->finalResultArray['result_raw'] = $this->dataArray;
	}

}
