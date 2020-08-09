<?php

/**
 * Datei für ...
 *
 * @version       1.0 / 2020-06-08
 * @author        akrys
 */
namespace FriendsOfRedaxo\addon\MediapoolExif\Exception;

use Exception;
use Throwable;

/**
 * Description of NotFoundException
 *
 * @author akrys
 */
class NotFoundException
	extends Exception
{
	/**
	 *
	 * @todo activate type hint if min PHP-Version > 7.4
	 * @var string
	 */
	public /* string */ $index;

	/**
	 * Konstruktor
	 * @param string $index
	 * @param string $message
	 * @param int $code
	 * @param Throwable $previous
	 */
	public function __construct(string $index, string $message = "", int $code = 0, Throwable $previous = NULL)
	{
		$this->index = $index;
		parent::__construct($message, $code, $previous);
	}

	/**
	 * Index alleine holen.
	 * Damit kann man in einem Catch-Block eigene Infos zusammenstellen.
	 * Was der Fehler ist, ergibt sich ja aus dem Exception-Namen.
	 * @return string
	 */
	public function getIndex(): string
	{
		return $this->index;
	}
}
