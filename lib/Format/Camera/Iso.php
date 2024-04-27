<?php

/**
 * Datei für ...
 *
 * @version       1.0 / 2020-06-13
 * @author        akrys
 */
namespace FriendsOfRedaxo\addon\MediapoolExif\Format\Camera;

use Exception;
use FriendsOfRedaxo\addon\MediapoolExif\Format\FormatInterface;

/**
 * Description of Iso
 *
 * @author akrys
 */
class Iso extends FormatInterface
{

	/**
	 * Daten formatieren
	 * @return string
	 * @throws Exception
	 */
	public function format(): string
	{
		if (!isset($this->data['ISOSpeedRatings'])) {
			throw new Exception('No iso setting found');
		}

		return $this->data['ISOSpeedRatings'];
	}
}
