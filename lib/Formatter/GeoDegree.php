<?php

/**
 * Datei für GEO
 *
 * @author        akrys
 */

namespace FriendsOfRedaxo\MediapoolExif\Formatter;

use Exception;


/**
 * Description of Geo
 *
 * Hinweis zu den Funktionen <tt>getLatSuffix</tt> und <tt>getLongSuffix</tt>:
 *
 * Diese Funktionen sind dazu da, um in einem eigenen Formatter überschreiben werden zu können. Damit ist es möglich,
 * der die Suffixe auch in anderer Sprache darstellen zu können.
 *
 * Als Beispiel:
 * Für das Deutsche ist eigentlich nur wichtig, dass das E (für East) zu O (für Osten) wird.
 * Im Niederländischen müsste aber auch das S (für South) zu einem Z (für Zuid) werden.
 *
 * Daher werden alle Himmelsrichtungen angepasst, auch wenn dadurch 'S' zu 'S' und 'W' zu 'W' gemappt wird.
 *
 * @author akrys
 */
class GeoDegree extends GeoBase
{

	/**
	 * Formatierung der Daten
	 * @return array<string, mixed>
	 * @throws Exception
	 */
	public function format(array $exifData): array
	{
		if (!$this->hasGeoData($exifData)) {
			throw new Exception('GPS not found');
		}

		$lat = $this->getLat($exifData);
		$long = $this->getLong($exifData);

		return [
			'lat' => $lat->degree . '° ' . $lat->minute . "' " . $lat->second . '" ' . $this->getLatSuffix($lat->ref),
			'long' => $long->degree . '° ' . $long->minute . "' " . $long->second . '" ' . $this->getLongSuffix($long->ref),
		];
	}

	/**
	 * Umformung in deutsche Suffixe für Nord, Süd
	 *
	 * Als eigene Funktion, damit man via Ableitung der Klasse eigene Suffixe (für andere Sprachen) einsetzen kann.
	 *
	 * @return string
	 */
	public function getLatSuffix(string $GPSLatitude_Ref): string
	{
		return match ($GPSLatitude_Ref) {
			'S' => 'S',
			'N' => 'N',
			default => $GPSLatitude_Ref,
		};
	}

	/**
	 * Umformung in deutsche Suffixe für Ost, West
	 *
	 * Als eigene Funktion, damit man via Ableitung der Klasse eigene Suffixe (für andere Sprachen) einsetzen kann.
	 *
	 * @return string
	 */
	public function getLongSuffix(string $GPSLatitude_Ref): string
	{
		return match ($GPSLatitude_Ref) {
			'W' => 'W',
			'E' => 'O',
			default => $GPSLatitude_Ref,
		};
	}
}
