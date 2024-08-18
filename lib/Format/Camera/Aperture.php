<?php

/**
 * Datei für ...
 *
 * @version       1.0 / 2020-06-13
 * @author        akrys
 */
namespace FriendsOfRedaxo\MediapoolExif\Format\Camera;

use Exception;
use FriendsOfRedaxo\MediapoolExif\Format\FormatInterface;

/**
 * Description of Aperture
 *
 * @author akrys
 */
class Aperture extends FormatInterface
{

	/**
	 * Daten formatieren
	 * @return string
	 * @throws Exception
	 */
	public function format(): string
	{
		if (!isset($this->data['FNumber'])) {
			throw new Exception('No aperture found');
		}

		$data = explode('/', $this->data['FNumber']);
		return 'f/'.number_format((float) $data[0] / (float) $data[1], 1);
	}
}
