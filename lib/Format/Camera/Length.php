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
 * @author        akrys
 */

/**
 * Description of Length
 *
 * @author akrys
 * @deprecated use \FriendsOfRedaxo\MediapoolExif\Formatter\Camera\Length instead
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
		$msg = "Deprecated class use \FriendsOfRedaxo\MediapoolExif\Formatter\Camera\Length instead";
		user_error($msg, E_USER_DEPRECATED);

		$formatter = new \FriendsOfRedaxo\MediapoolExif\Formatter\Camera\Length();
		return $formatter->format($this->data);
	}
}
