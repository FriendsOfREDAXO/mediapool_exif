<?php

/**
 * Datei für GEO
 *
 * @author        akrys
 */

namespace FriendsOfRedaxo\MediapoolExif\Format;

use Exception;

/**
 * Description of Geo
 *
 * @author akrys
 * @deprecated use \FriendsOfRedaxo\MediapoolExif\Formatter\Geo instead
 */
class Geo extends FormatBase
{

	/**
	 * Formatierung der Daten
	 * @return array<string, mixed>
	 * @throws Exception
	 */
	public function format(): array
	{
		$msg = "Deprecated class use \FriendsOfRedaxo\MediapoolExif\Formatter\Geo instead";
		user_error($msg, E_USER_DEPRECATED);

		$formatter = new \FriendsOfRedaxo\MediapoolExif\Formatter\Geo();
		return $formatter->format($this->data);
	}
}
