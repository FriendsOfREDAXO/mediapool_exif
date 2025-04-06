<?php

/**
 * Datei für ...
 *
 * @author        akrys
 */
namespace FriendsOfRedaxo\MediapoolExif\Formatter;

use Exception;
use FriendsOfRedaxo\MediapoolExif\Formatter\Camera\Aperture;
use FriendsOfRedaxo\MediapoolExif\Formatter\Camera\Exposure;
use FriendsOfRedaxo\MediapoolExif\Formatter\Camera\Iso;
use FriendsOfRedaxo\MediapoolExif\Formatter\Camera\Length;
use FriendsOfRedaxo\MediapoolExif\Formatter\Interface\ArrayFormatterInterface;

/**
 * Description of Camera
 *
 * @author akrys
 */
class Camera implements ArrayFormatterInterface
{

	/**
	 * Daten formatieren
	 * @param array<string, mixed> $exifData
	 * @return array<string, mixed>
	 * @throws Exception
	 */
	public function format(array $exifData): array
	{
		if (!isset($exifData['Make']) && !isset($exifData['Model'])) {
			throw new Exception('No camera data found');
		}

		return [
			'make' => $exifData['Make'],
			'model' => $exifData['Model'],
			'iso' => (new Iso())->format($exifData),
			'aperture' => (new Aperture())->format($exifData),
			'exposure' => (new Exposure())->format($exifData),
			'length' => (new Length())->format($exifData),
		];
	}
}
