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
 * Description of Length
 *
 * @author akrys
 */
class Length implements StandardFormtterInterface
{

	/**
	 * Daten formatieren
	 * @param array<string, mixed> $data
	 * @return string
	 * @throws Exception
	 */
	public function format(array $data): string
	{
		if (!isset($data['FocalLength'])) {
			throw new Exception('No focial length found');
		}

		$data = explode('/', $data['FocalLength']);
		$value = (float) $data[0] / (float) $data[1];

		return $value.' mm';
	}
}
