<?php

/**
 * Datei für GEO
 *
 * @author        akrys
 */
namespace FriendsOfRedaxo\MediapoolExif\Formatter;

use Exception;
use FriendsOfRedaxo\MediapoolExif\Formatter\Interface\ArrayFormatterInterface;

/**
 * Description of Geo
 *
 * @author akrys
 */
class Geo implements ArrayFormatterInterface
{

	/**
	 * Formatierung der Daten
	 * @param array<string, mixed> $data
	 * @return array<string, mixed>
	 * @throws Exception
	 */
	public function format(array $data): array
	{
		if (!isset($data['GPSLatitude']) ||
			!isset($data['GPSLatitudeRef']) ||
			!isset($data['GPSLongitude']) ||
			!isset($data['GPSLongitudeRef'])) {
			throw new Exception('GPS not found');
		}

		$GPSLatitude = $data['GPSLatitude'];
		$GPSLatitude_Ref = $data['GPSLatitudeRef'];
		$GPSLongitude = $data['GPSLongitude'];
		$GPSLongitude_Ref = $data['GPSLongitudeRef'];

		$GPSLatfaktor = 1; //N
		if ($GPSLatitude_Ref == 'S') {
			$GPSLatfaktor = -1;
		}

		$GPSLongfaktor = 1; //E
		if ($GPSLongitude_Ref == 'W') {
			$GPSLongfaktor = -1;
		}

		$GPSLatitude_h = explode("/", $GPSLatitude[0]);
		$GPSLatitude_m = explode("/", $GPSLatitude[1]);
		$GPSLatitude_s = explode("/", $GPSLatitude[2]);

		$GPSLat_h = (float) $GPSLatitude_h[0] / (float) $GPSLatitude_h[1];
		$GPSLat_m = (float) $GPSLatitude_m[0] / (float) $GPSLatitude_m[1];
		$GPSLat_s = (float) $GPSLatitude_s[0] / (float) $GPSLatitude_s[1];

		$GPSLatGrad = $GPSLatfaktor * ($GPSLat_h + ($GPSLat_m + ($GPSLat_s / 60)) / 60);

		$GPSLongitude_h = explode("/", $GPSLongitude[0]);
		$GPSLongitude_m = explode("/", $GPSLongitude[1]);
		$GPSLongitude_s = explode("/", $GPSLongitude[2]);
		$GPSLong_h = (float) $GPSLongitude_h[0] / (float) $GPSLongitude_h[1];
		$GPSLong_m = (float) $GPSLongitude_m[0] / (float) $GPSLongitude_m[1];
		$GPSLong_s = (float) $GPSLongitude_s[0] / (float) $GPSLongitude_s[1];
		$GPSLongGrad = $GPSLongfaktor * ($GPSLong_h + ($GPSLong_m + ($GPSLong_s / 60)) / 60);

		return [
			'lat' => number_format($GPSLatGrad, 6, '.', ''),
			'long' => number_format($GPSLongGrad, 6, '.', ''),
		];
	}
}
