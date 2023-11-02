<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace FriendsOfRedaxo\addon\MediapoolExif\Format\Camera;

use Exception;
use FriendsOfRedaxo\addon\MediapoolExif\Enum\Format;
use FriendsOfRedaxo\addon\MediapoolExif\Format\FormatInterface;

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
class Length extends FormatInterface
{

	/**
	 * Daten formatieren
	 * @return string
	 * @throws Exception
	 */
	public function format()
	{
		if (!isset($this->data['FocalLength'])) {
			throw new Exception('No aperture found');
		}

		$data = explode('/', $this->data['FocalLength']);
		$value = (float) $data[0] / (float) $data[1];
		switch ($this->format) {
			case Format::READABLE:
				return $value.' mm';
				break;
			case Format::RAW:
				return $value;
				break;
		}
	}
}
