<?php

namespace PragmaRX\ZIPcode\Support;

interface FinderInterface
{
	/**
	 * Find a zip.
	 *
	 * @param $zip
	 * @param null $finderService
	 * @return \PragmaRX\ZIPcode\Support\Result
	 */
	public function find($zip, $finderService = null);

}