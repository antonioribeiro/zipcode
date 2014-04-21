<?php

namespace PragmaRX\ZIPcode\Support;

interface HttpInterface
{
	/**
	 * Consume an url.
	 *
	 * @param $url
	 * @return bool|mixed
	 */
	public function consume($url);

	/**
	 * Check if a site is up.
	 *
	 * @param $url
	 * @return bool
	 */
	public function ping($url);

	/**
	 * User agent getter.
	 *
	 * @return mixed
	 */
	public function getUserAgent();

	/**
	 * User agent setter.
	 *
	 * @param mixed $userAgent
	 */
	public function setUserAgent($userAgent);

}