<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */
namespace FriendsOfRedaxo\MediapoolExif\Format;

/**
 * Datei für ...
 *
 * @version       1.0 / 2024-08-23
 * @author        akrys
 */

use FriendsOfRedaxo\MediapoolExif\Enum\Format;
use FriendsOfRedaxo\MediapoolExif\Exception\InvalidClassException;

/**
 * Description of FormatBase
 *
 * @author akrys
 */
abstract class FormatBase
{

	/**
	 * Konstruktor
	 * @param array<string, mixed> $data Exif-Daten
	 */
	public function __construct(
		protected array $data
	) {

	}

	/**
	 * Daten holen
	 *
	 * Hinweise zu Parametern:
	 *
	 * $data:
	 * Die hier hinein gegebenen EXIF-Daten kommen i.d.R. aus der Datenbank (Spalte rex_media.exif).
	 * Es kann aber auch sein, dass sie direkt aus der Datei stammen. z.B. für die automatische Koordinaten-Errechnung.
	 * Im Prinzip spielt es aber keine Rolle, wo die Daten genau her kommen. Wichtig ist nur, dass sie hier ankommen.
	 *
	 * $className:
	 * Bei ClassName geht es um die Formatter-Klasse, die verwendet werden soll. Wichtig ist, dass sie das
	 * FormatInterface implementiert. Damit kann die Funktion format immer aufgerufen werden.
	 *
	 * @param array<string, mixed> $data exif-Daten-Array
	 * @param string $className Formatter Namespace
	 * @throws InvalidClassException
	 */
	public static function get(
		$data,
		string $className = ''
	): FormatBase
	{
		if (class_exists($className)) {
			$object = new $className($data);
			if (is_a($object, FormatBase::class)) {
				return $object;
			}
		}
		throw new InvalidClassException($className);
	}

	/**
	 * Formatierung der Daten.
	 *
	 * Hier ist der Algorithmus hinterlegt, nach welchem der bzw. die Werte formatiert werden
	 *
	 * Die Rückgabe kann bei einzelwerten ein string, sont ein array sein.
	 *
	 * @return string|array<string, mixed>
	 */
	abstract public function format(): string|array;
}
