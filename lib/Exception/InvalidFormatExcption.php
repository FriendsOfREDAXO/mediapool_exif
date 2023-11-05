<?php

/**
 * Datei für ...
 *
 * @version       1.0 / 2020-06-12
 * @author        akrys
 */
namespace FriendsOfRedaxo\addon\MediapoolExif\Exception;

use Exception;
use FriendsOfRedaxo\addon\MediapoolExif\Enum\Format;
use Throwable;

/**
 * Description of InvalidFormatExcption
 *
 * @author akrys
 */
class InvalidFormatExcption extends Exception
{
	/**
	 *
	 * @todo activate type hint if min PHP-Version > 7.4
	 * @var Format
	 */
	public Format $format;

	/**
	 * Konstruktor
	 * @param string $format
	 * @param string $message
	 * @param int $code
	 * @param Throwable $previous
	 */
	public function __construct(Format $format, string $message = "", int $code = 0, Throwable $previous = NULL)
	{
		$this->format = $format;
		if ($message === '') {
			$message = 'Invalid Format: '.$format->value;
		}
		parent::__construct($message, $code, $previous);
	}

	/**
	 * Formatname
	 * @return Format
	 */
	public function getFormat(): Format
	{
		return $this->format;
	}
}
