<?php

/**
 * Datei für ...
 *
 * @version       1.0 / 2020-06-13
 * @author        akrys
 */
namespace FriendsOfRedaxo\MediapoolExif\Formatter\Camera;

use Exception;
use FriendsOfRedaxo\MediapoolExif\Interface\Formatter\StandardFormtterInterface;

/**
 * Description of Exposure
 *
 * @author akrys
 */
class Exposure implements StandardFormtterInterface
{

	/**
	 * Daten formatieren
	 * @param array<string, mixed> $exif
	 * @return string
	 * @throws Exception
	 */
	public function format(array $exif): string
	{
		if (!isset($exif['ExposureTime'])) {
			throw new Exception('No exposure time found');
		}

		$data = explode('/', $exif['ExposureTime']);
		return $data[0].'/'.$data[1].' s';
	}
}
