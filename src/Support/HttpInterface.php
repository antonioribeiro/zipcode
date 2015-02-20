<?php

namespace PragmaRX\ZipCode\Support;

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
