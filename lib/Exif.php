<?php

/**
 * Datei für ...
 *
 * @version       1.0 / 2020-06-08
 * @author        akrys
 */
namespace FriendsOfRedaxo\MediapoolExif;

use FriendsOfRedaxo\MediapoolExif\Enum\MediaFetchMode;
use FriendsOfRedaxo\MediapoolExif\Enum\ReturnMode;
use FriendsOfRedaxo\MediapoolExif\ExifData;
use rex_media;
use rex_sql;

/**
 * Description of Exif
 *
 * @author akrys
 */
class Exif
{

	/**
	 * Alternative Datenerhebung, analog zu rex_media::get()
	 *
	 * @param rex_media $media
	 * @return ExifData
	 */
	public static function get(rex_media $media, ReturnMode $mode = ReturnMode::THROW_EXCEPTION): ExifData
	{
		return new ExifData($media, $mode);
	}

	/**
	 *
	 * @param MediaFetchMode $mode
	 * @return list<array<int|string, scalar|null>>|array<int|string, scalar|null>
	 */
	public static function getMediaToRead(MediaFetchMode $mode = MediaFetchMode::NULL_ONLY): array
	{
		$rexSQL = rex_sql::factory();

		switch ($mode) {
			case MediaFetchMode::ALL:
				$sql = 'select filename from rex_media';
				break;
			case MediaFetchMode::NULL_ONLY:
			default:
				$sql = 'select filename from rex_media where exif is null';
				break;
		}
		return $rexSQL->getArray($sql);
	}
}
