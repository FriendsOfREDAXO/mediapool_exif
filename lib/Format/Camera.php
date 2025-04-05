<?php

/**
 * Datei für ...
 *
 * @author        akrys
 */
namespace FriendsOfRedaxo\MediapoolExif\Format;

use Exception;
use FriendsOfRedaxo\MediapoolExif\Format\Camera\Aperture;
use FriendsOfRedaxo\MediapoolExif\Format\Camera\Exposure;
use FriendsOfRedaxo\MediapoolExif\Format\Camera\Iso;
use FriendsOfRedaxo\MediapoolExif\Format\Camera\Length;

/**
 * Description of Camera
 *
 * @author akrys
 * @deprecated use \FriendsOfRedaxo\MediapoolExif\Formatter\Geo instead
 */
class Camera extends FormatBase
{

	/**
	 * Daten formatieren
	 * @return array<string, mixed>
	 * @throws Exception
	 */
	public function format(): array
	{
		$msg = "Deprecated class use \FriendsOfRedaxo\MediapoolExif\Formatter\Geo instead";
		user_error($msg, E_USER_DEPRECATED);

		$formatter = new \FriendsOfRedaxo\MediapoolExif\Formatter\Camera();
		return $formatter->format($this->data);
	}
}
