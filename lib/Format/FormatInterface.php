<?php

/**
 * Datei für ...
 *
 * @version       1.0 / 2020-06-12
 * @author        akrys
 */
namespace FriendsOfRedaxo\addon\MediapoolExif\Format;

use FriendsOfRedaxo\addon\MediapoolExif\Exception\InvalidFormatExcption;

/**
 * Description of FormatBase
 *
 * @author akrys
 */
abstract class FormatInterface
{
	/**
	 * Exif-Daten
	 * @var array
	 */
	protected /* array */ $data;

	/**
	 * Formatierung
	 * @var string
	 */
	protected /* string */ $format;

	/**
	 * Konstruktor
	 * @param array $data
	 * @param string $format
	 */
	public function __construct(array $data, string $format = null)
	{
		$this->data = $data;
		$this->format = $format;
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
	 * $type:
	 * Bei Type geht es um die Formatter-Klasse, die verwendet werden soll. Wichtig ist, dass siek das FormatInterface
	 * implementiert. Damit kann die Funktion format immer aufgerufen werden.
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
	 * @param string $data exif-Daten-Array
	 * @param string $type KlasseName des Formatters
	 * @param string $format Format-Parameter
	 * @return \FriendsOfRedaxo\addon\MediapoolExif\Format\className
	 * @throws InvalidFormatExcption
	 */
	public static function get($data, $type, $format = null): FormatInterface
	{
		$className = __NAMESPACE__.'\\'.ucfirst($type);
		if (class_exists($className)) {
			$object = new $className($data, $format);
			if (is_a($object, FormatInterface::class)) {
				return $object;
			}
		}
		throw new InvalidFormatExcption($className);
	}

	/**
	 * Formatierung der Daten.
	 *
	 * Hier ist der Algorithmus hinterlegt, nach welchem der bzw. die Werte formatiert werden
	 *
	 * Die Rückgabe kann bei einzelwerten ein string, sont ein array sein.
	 *
	 * @return mixed
	 */
	abstract public function format();
}
