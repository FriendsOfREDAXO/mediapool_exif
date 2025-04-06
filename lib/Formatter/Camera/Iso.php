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
 * Description of Iso
 *
 * @author akrys
 */
class Iso implements StandardFormatterInterface
{

	/**
	 * Daten formatieren
	 * @param array<string, mixed> $exifData
	 * @return string
	 * @throws Exception
	 */
	public function format(array $exifData): string
	{
		if (!isset($exifData['ISOSpeedRatings'])) {
			throw new Exception('No iso setting found');
		}

		return $exifData['ISOSpeedRatings'];
	}
}
