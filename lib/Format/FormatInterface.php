<?php

namespace FriendsOfRedaxo\MediapoolExif\Format;

/**
 * Datei für ...
 *
 * @version       1.0 / 2020-06-12
 * @author        akrys
 */

use FriendsOfRedaxo\MediapoolExif\Enum\Format;
use FriendsOfRedaxo\MediapoolExif\Exception\InvalidClassException;

/**
 * Description of FormatBase
 *
 * @author akrys
 * @deprecated Das hier ist kein Interface, es ist eine abstrakte Basis-Klasse. FormatBase ist der bessere Name
 */
abstract class FormatInterface extends FormatBase // remove in v4
{

	/**
	 * Konstruktor
	 * @param array<string, mixed> $data Exif-Daten
	 * @param Format $format Formatierung (deprected)
	 */
	public function __construct(
		array $data,
		protected /** @deprecated since version 3.1 */ Format $format = Format::READABLE
	) {
		parent::__construct($data);

		$backtrace = debug_backtrace();
		$backtraceText = '';
		$i = 0;
		foreach ($backtrace as $key => $item) {
			if (isset($backtrace[$key]['file']) && isset($backtrace[$key]['line'])) {
				if (stristr($backtrace[$key]['file'], '/mediapool_exif/')) {
					continue;
				}

				$backtraceText = 'in '.$backtrace[$key]['file'].': '.$backtrace[$key]['line'];
				break;
			}
			$i++;
		}

		$msg = FormatInterface::class.' is deprected. Use '.FormatBase::class.' for extension.';
		if ($backtraceText) {
			$msg .= '('.$backtraceText.')';
		}
		user_error($msg, E_USER_DEPRECATED);
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
	 * @param Format $format Format-Parameter (deprected)
	 * @return FormatInterface
	 * @throws InvalidClassException
	 * @deprecated since version 3.1
	 */
	public static function get(
		$data,
		string $className = '',
		/** @deprecated since version 3.1 */
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
}
