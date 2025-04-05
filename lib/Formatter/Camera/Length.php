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
	 * Daten formatieren
	 * @param array<string, mixed> $exifData
	 * @return string
	 * @throws Exception
	 */
	public function format(array $exifData): string
	{
		if (!isset($exifData['FocalLength'])) {
			throw new Exception('No focial length found');
		}

		$exifData = explode('/', $exifData['FocalLength']);
		$value = (float) $exifData[0] / (float) $exifData[1];

		return $value.' mm';
	}
}
