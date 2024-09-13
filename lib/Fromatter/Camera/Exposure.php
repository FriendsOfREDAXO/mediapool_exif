<?php

/**
 * Datei für ...
 *
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
		if ($data[0] !== '1' || ($data[0] === '1' && $data[1] < 3)) {
			return preg_replace('/,0$/', '', number_format($data[0] / $data[1], 1, ',', '.')).' s';
		}
		return $data[0].'/'.$data[1].' s';
	}
}
