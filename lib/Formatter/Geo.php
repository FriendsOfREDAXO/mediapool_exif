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
 * @author akrys
 */
class Geo extends GeoBase
{

	/**
	 * Formatierung der Daten
	 * @param array<string, mixed> $exifData
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

		$GPSLatfaktor = 1; //N
		if ($lat->ref == 'S') {
			$GPSLatfaktor = -1;
		}

		$GPSLongfaktor = 1; //E
		if ($long->ref == 'W') {
			$GPSLongfaktor = -1;
		}

		$GPSLatGrad = $GPSLatfaktor * ($lat->degree + ($lat->minute + ($lat->second/ 60)) / 60);
		$GPSLongGrad = $GPSLongfaktor * ($long->degree + ($long->minute + ($long->second / 60)) / 60);

		return [
			'lat' => number_format($GPSLatGrad, 6, '.', ''),
			'long' => number_format($GPSLongGrad, 6, '.', ''),
		];
	}
}
