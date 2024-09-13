<?php

/**
 * Datei für ...
 *
 * @author        akrys
 */
namespace FriendsOfRedaxo\MediapoolExif\Formatter\Camera;

use Exception;
use FriendsOfRedaxo\MediapoolExif\Formatter\Interface\StandardFormtterInterface;

/**
 * Description of Aperture
 *
 * @author akrys
 */
class Aperture implements StandardFormtterInterface
{

	/**
	 * Daten formatieren
	 * @param array<string, mixed> $exif
	 * @return string
	 * @throws Exception
	 */
	public function format(array $exif): string
	{
		if (!isset($exif['FNumber'])) {
			throw new Exception('No aperture found');
		}

		$data = explode('/', $exif['FNumber']);
		return 'f/'.number_format((float) $data[0] / (float) $data[1], 1);
	}
}
