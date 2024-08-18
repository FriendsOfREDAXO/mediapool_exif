<?php

/**
 * Datei für ...
 *
 * @version       1.0 / 2020-06-12
 * @author        akrys
 */
namespace FriendsOfRedaxo\MediapoolExif\Format;

use FriendsOfRedaxo\MediapoolExif\Enum\Format;
use FriendsOfRedaxo\MediapoolExif\Exception\InvalidClassException;

/**
 * Description of FormatBase
 *
 * @author akrys
 */
abstract class FormatInterface
{

	/**
	 * Konstruktor
	 * @param array<string, mixed> $data Exif-Daten
	 * @param Format $format Formatierung
	 */
	public function __construct(
		protected array $data,
		#[Deprecated]
		protected Format $format = Format::READABLE
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
	 * $format:
	 * Beim Format-Parameter kann man einen Hinweis liefern, wie die Ausgabe zu formatieren ist.
	 *
	 * Bei Geo-Koordinaten wären z.B. folgende Ansichten denkbar. Egal, bei welchen Werten man bestimmte Formatierungen
	 * braucht, mit den Format-Parameter könnte man das übertragen
	 * <ul>
	 *    <li>decimal: 51.1109221 / 8.6821267</li>
	 *    <li>degree: 51° 06' 39.32" N / 8° 40' 55.656 E</li>
	 * <ul>
	 *
	 * @param array<string, mixed> $data exif-Daten-Array
	 * @param string $className Formatter Namespace
	 * @param Format $format Format-Parameter deprected
	 * @return FormatInterface
	 * @throws InvalidClassException
	 */
	public static function get(
		$data,
		string $className = '',
		#[Deprecated]
		Format $format = Format::READABLE
	): FormatInterface
	{
		if (class_exists($className)) {
			$object = new $className($data, $format);
			if (is_a($object, FormatInterface::class)) {
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
