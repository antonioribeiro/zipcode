<?php

namespace PragmaRX\ZipCode\Support;

interface FinderInterface
{
	/**
	 * Find a zip.
	 *
	 * @param $zip
	 * @param null $finderService
	 * @return \PragmaRX\ZipCode\Support\Result
	 */
	public function find($zip, $finderService = null);

}
