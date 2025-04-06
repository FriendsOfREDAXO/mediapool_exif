<?php

namespace FriendsOfRedaxo\MediapoolExif\Formatter;

use FriendsOfRedaxo\MediapoolExif\Formatter\Interface\ArrayFormatterInterface;
use FriendsOfRedaxo\MediapoolExif\Model\GeoCoordinate;

abstract class GeoBase implements ArrayFormatterInterface
{

	/**
	 * Geo-Daten vorhanden?
	 *
	 * @param array<string, mixed> $exifData
	 * @return bool
	 */
	protected function hasGeoData(array $exifData)
	{
		if (!isset($exifData['GPSLatitude']) ||
			!isset($exifData['GPSLatitudeRef']) ||
			!isset($exifData['GPSLongitude']) ||
			!isset($exifData['GPSLongitudeRef'])) {
			return false;
		}
		return true;
	}

	/**
	 * Nord/Süd-Grad-Wert holen
	 *
	 * @param array<string, mixed> $exifData
	 * @return GeoCoordinate
	 */
	public function getLat(array $exifData): GeoCoordinate
	{
		return $this->getDegreeValue($exifData['GPSLatitude'], $exifData['GPSLatitudeRef']);
	}

	/**
	 * Ost/West-Grad-Wert holen
	 *
	 * @param array<string, mixed> $exifData
	 * @return GeoCoordinate
	 */

	public function getLong(array $exifData): GeoCoordinate
	{
		return $this->getDegreeValue($exifData['GPSLongitude'], $exifData['GPSLongitudeRef']);
	}

	/**
	 * Basis-Berechnung durchführen
	 *
	 * @param array<int, string>$value
	 * @param string $ref
	 * @return GeoCoordinate
	 */
	private function getDegreeValue(array $value, string $ref): GeoCoordinate
	{
		$h = explode("/", $value[0]);
		$m = explode("/", $value[1]);
		$s = explode("/", $value[2]);

		return new GeoCoordinate(
			degree: (float)$h[0] / (float)$h[1],
			minute: (float)$m[0] / (float)$m[1],
			second: (float)$s[0] / (float)$s[1],
			ref: $ref
		);
	}

}
