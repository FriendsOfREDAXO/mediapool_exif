<?php

/**
 * Datei für ...
 *
 * @author        akrys
 */
namespace FriendsOfRedaxo\MediapoolExif\Formatter\Camera;

use Exception;
use FriendsOfRedaxo\MediapoolExif\Formatter\Interface\StandardFormatterInterface;

/**
 * Description of Iso
 *
 * @author akrys
 */
class Iso implements StandardFormatterInterface
{

	/**
	 * Daten formatieren
	 * @param array<string, mixed> $data
	 * @return string
	 * @throws Exception
	 */
	public function format(array $data): string
	{
		if (!isset($data['ISOSpeedRatings'])) {
			throw new Exception('No iso setting found');
		}

		return $data['ISOSpeedRatings'];
	}
}
