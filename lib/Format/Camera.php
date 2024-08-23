<?php

/**
 * Datei für ...
 *
 * @version       1.0 / 2020-06-13
 * @author        akrys
 */
namespace FriendsOfRedaxo\MediapoolExif\Format;

use Exception;
use FriendsOfRedaxo\MediapoolExif\Format\Camera\Aperture;
use FriendsOfRedaxo\MediapoolExif\Format\Camera\Exposure;
use FriendsOfRedaxo\MediapoolExif\Format\Camera\Iso;
use FriendsOfRedaxo\MediapoolExif\Format\Camera\Length;

/**
 * Description of Camera
 *
 * @author akrys
 */
class Camera extends FormatBase
{

	/**
	 * Daten formatieren
	 * @return array<string, mixed>
	 * @throws Exception
	 */
	public function format(): array
	{
		if (!isset($this->data['Make']) && !isset($this->data['Model'])) {
			throw new Exception('No camera data found');
		}

		return [
			'make' => $this->data['Make'],
			'model' => $this->data['Model'],
			'iso' => (new Iso($this->data))->format(),
			'aperture' => (new Aperture($this->data))->format(),
			'exposure' => (new Exposure($this->data))->format(),
			'length' => (new Length($this->data))->format(),
		];
	}
}
