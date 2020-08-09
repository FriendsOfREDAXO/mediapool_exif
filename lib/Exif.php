<?php

/**
 * Datei für ...
 *
 * @version       1.0 / 2020-06-08
 * @author        akrys
 */
namespace FriendsOfRedaxo\addon\MediapoolExif;

use FriendsOfRedaxo\addon\MediapoolExif\ExifData;

/**
 * Description of Exif
 *
 * @author akrys
 */
class Exif
{
	const MODE_THROW_EXCEPTION = 1000; //should be default
	const MODE_RETURN_NULL = 1001;
	const MODE_RETURN_FALSE = 1002;
	const MODE_RETURN_ZERO = 1003;
	const MODE_RETURN_MINUS = 1004;
	const MODE_RETURN_EMPTY_STRING = 1005;
	const MODE_RETURN_EMPTY_ARRAY = 1006;
	const GETMEDIA_MODE_NULL_ONLY = 1000;
	const GETMEDIA_MODE_ALL = 1001;

	/**
	 * Alternative Datenerhebung, analog zu rex_media::get()
	 *
	 * @param \FriendsOfRedaxo\addon\MediapoolExif\rex_media $media
	 * @return ExifData
	 */
	public static function get(rex_media $media, $mode = self::MODE_THROW_EXCEPTION): ExifData
	{
		return new ExifData($media, $mode);
	}

	/**
	 *
	 * @param type $mode
	 * @return array
	 */
	public static function getMediaToRead($mode = self::GETMEDIA_MODE_NULL_ONLY): array
	{
		$rexSQL = rex_sql::factory();

		switch ($mode) {
			case self::GETMEDIA_MODE_ALL:
				$sql = 'select filename from rex_media';
				break;
			case self::GETMEDIA_MODE_NULL_ONLY:
			default:
				$sql = 'select filename from rex_media where exif is null';
				break;
		}
		return $rexSQL->getArray($sql);
	}
}
