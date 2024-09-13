<?php

/**
 * Datei für ...
 *
 * @author        akrys
 */
namespace FriendsOfRedaxo\MediapoolExif\Formatter;

use Exception;
use FriendsOfRedaxo\MediapoolExif\Format\Camera\Aperture;
use FriendsOfRedaxo\MediapoolExif\Format\Camera\Exposure;
use FriendsOfRedaxo\MediapoolExif\Format\Camera\Iso;
use FriendsOfRedaxo\MediapoolExif\Format\Camera\Length;
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
	 * @param array<string, mixed> $data
	 * @return array<string, mixed>
	 * @throws Exception
	 */
	public function format(array $data): array
	{
		if (!isset($data['Make']) && !isset($data['Model'])) {
			throw new Exception('No camera data found');
		}

		return [
			'make' => $data['Make'],
			'model' => $data['Model'],
			'iso' => (new Iso($data))->format(),
			'aperture' => (new Aperture($data))->format(),
			'exposure' => (new Exposure($data))->format(),
			'length' => (new Length($data))->format(),
		];
	}
}
