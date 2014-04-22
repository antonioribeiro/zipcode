<?php

namespace PragmaRX\ZIPcode\Support;

interface FinderInterface
{
	/**
	 * Find a zip.
	 *
	 * @param $zip
	 * @param null $finderService
	 * @return mixed
	 */
	public function find($zip, $finderService);

	/**
	 * Set the zip instance.
	 *
	 * @param Zip $zip
	 */
	public function setZip($zip);
}