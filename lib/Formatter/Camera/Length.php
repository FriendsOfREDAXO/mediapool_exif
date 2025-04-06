<?php

/**
 * Datei für ...
 *
 * @author        akrys
 */
namespace FriendsOfRedaxo\MediapoolExif\Formatter\Camera;

use Exception;
use FriendsOfRedaxo\MediapoolExif\Formatter\Interface\StandardFormatterInterface;

/**
 * Description of Length
 *
 * @author akrys
 */
class Length implements StandardFormatterInterface
{

	/**
	 * Basis-Wert ermitteln.
	 *
	 * Kann in einem abgeleiteten Formatter verwendet werden um den Basis-Wert zu bekommen
	 *
	 * @param array<string, mixed> $exifData
	 * @return string
	 */
	public function getValue(array $exifData): string
	{
		if (!isset($exifData['FocalLength'])) {
			throw new Exception('No focial length found');
		}

		$exifData = explode('/', $exifData['FocalLength']);
		$value = (float) $exifData[0] / (float) $exifData[1];
		return (string) $value;
	}

	/**
	 * Daten formatieren
	 * @param array<string, mixed> $exifData
	 * @return string
	 * @throws Exception
	 */
	public function format(array $exifData): string
	{
		return $this->getValue($exifData).' mm';
	}
}
