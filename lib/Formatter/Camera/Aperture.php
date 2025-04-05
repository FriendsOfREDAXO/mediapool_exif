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
 * Description of Aperture
 *
 * @author akrys
 */
class Aperture implements StandardFormatterInterface
{

	/**
	 * Daten formatieren
	 * @param array<string, mixed> $exifData
	 * @return string
	 * @throws Exception
	 */
	public function format(array $exifData): string
	{
		if (!isset($exifData['FNumber'])) {
			throw new Exception('No aperture found');
		}

		$data = explode('/', $exifData['FNumber']);
		return 'f/'.number_format((float) $data[0] / (float) $data[1], 1);
	}
}
