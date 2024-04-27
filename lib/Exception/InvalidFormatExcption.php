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
	 * Konstruktor
	 * @param ?Format $format
	 * @param string $message
	 * @param int $code
	 * @param Throwable $previous
	 */
	public function __construct(
		private ?Format $format,
		string $message = "",
		int $code = 0,
		Throwable $previous = null
	) {
		if ($this->format === null) {
			$this->format = Format::UNDEFINED;
		}
		if ($message === '') {
			$message = 'Invalid Format: '.$this->format->value;
		}
		parent::__construct($message, $code, $previous);
	}

	/**
	 * Formatname
	 * @return Format|null
	 */
	public function getFormat(): ?Format
	{
		return $this->format;
	}
}
