<?php

/**
 * Datei für ...
 *
 * @version       1.0 / 2020-06-12
 * @author        akrys
 */
namespace FriendsOfRedaxo\addon\MediapoolExif\Exception;

use Exception;

/**
 * Description of InvalidFormatExcption
 *
 * @author akrys
 */
class InvalidFormatExcption
	extends Exception
{
	/**
	 *
	 * @todo activate type hint if min PHP-Version > 7.4
	 * @var string
	 */
	public /* string */ $format;

	/**
	 * Konstruktor
	 * @param string $format
	 * @param string $message
	 * @param int $code
	 * @param \Throwable $previous
	 */
	public function __construct(string $format, string $message = "", int $code = 0, \Throwable $previous = NULL)
	{
		$this->format = $format;
		parent::__construct($message, $code, $previous);
	}

	/**
	 * Formatname
	 * @return string
	 */
	public function getFormat(): string
	{
		return $this->format;
	}
}
