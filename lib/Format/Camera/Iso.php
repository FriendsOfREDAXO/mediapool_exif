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
 * Description of Iso
 *
 * @author akrys
 * @deprecated use \FriendsOfRedaxo\MediapoolExif\Formatter\Camera\Iso instead
 */
class Iso extends FormatBase
{

	/**
	 * Daten formatieren
	 * @return string
	 * @throws Exception
	 */
	public function format(): string
	{
		$msg = "Deprecated class use \FriendsOfRedaxo\MediapoolExif\Formatter\Camera\Iso instead";
		user_error($msg, E_USER_DEPRECATED);

		$formatter = new \FriendsOfRedaxo\MediapoolExif\Formatter\Camera\Iso();
		return $formatter->format($this->data);
	}
}
