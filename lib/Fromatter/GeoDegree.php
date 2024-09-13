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
class GeoDegree implements ArrayFormatterInterface
{

	/**
	 * Formatierung der Daten
	 * @return array<string, mixed>
	 * @throws Exception
	 */
	public function format(): array
	{
		if (!isset($this->data['GPSLatitude']) ||
			!isset($this->data['GPSLatitudeRef']) ||
			!isset($this->data['GPSLongitude']) ||
			!isset($this->data['GPSLongitudeRef'])) {
			throw new Exception('GPS not found');
		}

		$GPSLatitude = $this->data['GPSLatitude'];
		$GPSLatitude_Ref = $this->data['GPSLatitudeRef'];
		$GPSLongitude = $this->data['GPSLongitude'];
		$GPSLongitude_Ref = $this->data['GPSLongitudeRef'];

		$latSuffix = $this->getLatSuffix($GPSLatitude_Ref);

		$longSuffix = $this->getLongSuffix($GPSLongitude_Ref);

		$GPSLatitude_h = explode("/", $GPSLatitude[0]);
		$GPSLatitude_m = explode("/", $GPSLatitude[1]);
		$GPSLatitude_s = explode("/", $GPSLatitude[2]);

		$GPSLat_h = (float) $GPSLatitude_h[0] / (float) $GPSLatitude_h[1];
		$GPSLat_m = (float) $GPSLatitude_m[0] / (float) $GPSLatitude_m[1];
		$GPSLat_s = (float) $GPSLatitude_s[0] / (float) $GPSLatitude_s[1];

		$GPSLongitude_h = explode("/", $GPSLongitude[0]);
		$GPSLongitude_m = explode("/", $GPSLongitude[1]);
		$GPSLongitude_s = explode("/", $GPSLongitude[2]);

		$GPSLong_h = (float) $GPSLongitude_h[0] / (float) $GPSLongitude_h[1];
		$GPSLong_m = (float) $GPSLongitude_m[0] / (float) $GPSLongitude_m[1];
		$GPSLong_s = (float) $GPSLongitude_s[0] / (float) $GPSLongitude_s[1];

		return [
			'lat' => $GPSLat_h.'º '.$GPSLat_m."' ".$GPSLat_s.'" '.$latSuffix,
			'long' => $GPSLong_h.'º '.$GPSLong_m."' ".$GPSLong_s.'" '.$longSuffix,
		];
	}

	/**
	 * Umformung in deutsche Suffixe für Nord, Süd
	 *
	 * Als eigene Funktion, damit man via Ableitung der Klasse eigene Suffixe (für andere Sprachen) einsetzen kann.
	 *
	 * @return type
	 */
	public function getLatSuffix($GPSLatitude_Ref)
	{
		return match ($GPSLatitude_Ref) {
			'S' => 'S',
			'N' => 'N',
		};
	}

	/**
	 * Umformung in deutsche Suffixe für Ost, West
	 *
	 * Als eigene Funktion, damit man via Ableitung der Klasse eigene Suffixe (für andere Sprachen) einsetzen kann.
	 *
	 * @return type
	 */
	public function getLongSuffix($GPSLatitude_Ref)
	{
		return match ($GPSLatitude_Ref) {
			'W' => 'W',
			'E' => 'O',
		};
	}
}
