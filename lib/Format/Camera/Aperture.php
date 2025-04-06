<?php

/**
 * Datei für ...
 *
 * @author        akrys
 */
namespace FriendsOfRedaxo\MediapoolExif\Format\Camera;

use Exception;
use FriendsOfRedaxo\MediapoolExif\Format\FormatBase;

/**
 * Description of Aperture
 *
 * @author akrys
 * @deprecated use \FriendsOfRedaxo\MediapoolExif\Formatter\Camera\Aperture
 */
class Aperture extends FormatBase
{

	/**
	 * Daten formatieren
	 * @return string
	 * @throws Exception
	 */
	public function format(): string
	{
		$msg = "Deprecated class use \FriendsOfRedaxo\MediapoolExif\Formatter\Camera\Aperture instead";
		user_error($msg, E_USER_DEPRECATED);

		$formatter = new \FriendsOfRedaxo\MediapoolExif\Formatter\Camera\Aperture();
		return $formatter->format($this->data);
	}
}
