<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace FriendsOfRedaxo\MediapoolExif\Formatter\Camera;

use Exception;
use FriendsOfRedaxo\MediapoolExif\Interface\Formatter\StandardFormtterInterface;

/**
 * Datei für ...
 *
 * @version       1.0 / 2020-06-13
 * @author        akrys
 */

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
