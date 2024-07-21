<?php

/**
 * Datei für ...
 *
 * @version       1.0 / 2020-06-13
 * @author        akrys
 */
namespace FriendsOfRedaxo\MediapoolExif\Format\Camera;

use Exception;
use FriendsOfRedaxo\MediapoolExif\Enum\Format;
use FriendsOfRedaxo\MediapoolExif\Format\FormatInterface;

/**
 * Description of Exposure
 *
 * @author akrys
 */
class Exposure extends FormatInterface
{

	/**
	 * Daten formatieren
	 * @return string
	 * @throws Exception
	 */
	public function format(): string
	{
		if (!isset($this->data['ExposureTime'])) {
			throw new Exception('No exposure time found');
		}

		$return = '';
		$data = explode('/', $this->data['ExposureTime']);
		switch ($this->format) {
			case Format::READABLE:
				$return= $data[0].'/'.$data[1].' s';
				break;
			case Format::RAW:
			default:
				$tmp = (float) $data[0] / (float) $data[1];
				$return = (string) $tmp;
				break;
		}
		return $return;
	}
}
