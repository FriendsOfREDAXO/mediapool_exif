<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */
namespace FriendsOfRedaxo\MediapoolExif\Exception;

use Exception;
use Throwable;

/**
 * Datei für ...
 *
 * @version       1.0 / 2024-03-31
 * @author        akrys
 */

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
		private string $class,
		string $message = "",
		int $code = 0,
		Throwable $previous = null
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
