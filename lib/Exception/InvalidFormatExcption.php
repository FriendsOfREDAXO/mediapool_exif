<?php

/**
 * Datei für ...
 *
 * @version       1.0 / 2020-06-12
 * @author        akrys
 */
namespace FriendsOfRedaxo\MediapoolExif\Exception;

use Exception;
use FriendsOfRedaxo\MediapoolExif\Enum\Format;
use Throwable;

/**
 * Description of InvalidFormatExcption
 *
 * @author akrys
 * @deprecated since version 3.1. Wird ersatzlos gestrichen. Ein Formatter ist für exakt ein Format zuständig, wenn man die Rohdaten braucht, kann man einen Rohdaten Formatter schreiben.
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
		private ?Format $format, string $message = "", int $code = 0, Throwable $previous = null
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
	 * @deprecated since version 3.1
	 */
	public function getFormat(): ?Format
	{
		return $this->format;
	}
}
