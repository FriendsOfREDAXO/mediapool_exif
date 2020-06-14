<?php

/**
 * Datei für ...
 *
 * @version       1.0 / 2020-06-13
 * @author        akrys
 */
namespace FriendsOfRedaxo\addon\MediapoolExif\Format\Camera;

use Exception;
use FriendsOfRedaxo\addon\MediapoolExif\Format\Camera;
use FriendsOfRedaxo\addon\MediapoolExif\Format\FormatInterface;

/**
 * Description of Exposure
 *
 * @author akrys
 */
class Exposure
	extends FormatInterface
{

	/**
	 * Daten formatieren
	 * @return string
	 * @throws Exception
	 */	public function format()
	{
		if (!isset($this->data['ExposureTime'])) {
			throw new Exception('No aperture found');
		}

		$data = explode('/', $this->data['ExposureTime']);
		switch ($this->format) {
			case Camera::TYPE_READABLE:
				return $data[0].'/'.$data[1].' s';
				break;
			case Camera::TYPE_NUMERIC:
				return (float) $data[0] / (float) $data[1];
				break;
		}
	}
}
