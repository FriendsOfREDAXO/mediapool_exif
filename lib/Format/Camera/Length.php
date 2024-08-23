<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace FriendsOfRedaxo\MediapoolExif\Format\Camera;

use Exception;
use FriendsOfRedaxo\MediapoolExif\Format\FormatBase;

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
class Length extends FormatBase
{

	/**
	 * Daten formatieren
	 * @return string
	 * @throws Exception
	 */
	public function format(): string
	{
		if (!isset($this->data['FocalLength'])) {
			throw new Exception('No focial length found');
		}

		$data = explode('/', $this->data['FocalLength']);
		$value = (float) $data[0] / (float) $data[1];

		return $value.' mm';
	}
}
