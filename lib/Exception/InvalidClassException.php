<?php

/**
 * Datei für ...
 *
 * @author        akrys
 */
namespace FriendsOfRedaxo\MediapoolExif\Exception;

use Exception;
use Throwable;

/**
 * Description of InvalidClassException
 *
 * @author akrys
 */
class InvalidClassException extends Exception
{

	/**
	 * Konstruktor
	 * @param string $class
	 * @param string $message
	 * @param int $code
	 * @param Throwable $previous
	 */
	public function __construct(
		private string $class, string $message = "", int $code = 0, Throwable $previous = null
	) {
		if ($message === '') {
			$message = 'Invalid class: '.$class;
		}
		parent::__construct($message, $code, $previous);
	}

	/**
	 * Formatname
	 * @return string
	 */
	public function getClass(): string
	{
		return $this->class;
	}
}
