<?php

/**
 * Datei für ...
 *
 * @version       1.0 / 2020-06-13
 * @author        akrys
 */
namespace FriendsOfRedaxo\MediapoolExif\Format\Camera;

use Exception;
use FriendsOfRedaxo\MediapoolExif\Format\FormatBase;

/**
 * Description of Exposure
 *
 * @author akrys
 */
class Exposure extends FormatBase
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

		$data = explode('/', $this->data['ExposureTime']);
		return $data[0].'/'.$data[1].' s';
	}
}
