<?php

namespace PragmaRX\ZIPcode\Support;

interface FinderInterface
{
	public function find($zip, $webService);

	public function setZip($zip);
}