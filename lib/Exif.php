<?php

/**
 * Datei für ...
 *
 * @version       1.0 / 2020-06-08
 * @author        akrys
 */
namespace FriendsOfRedaxo\addon\MediapoolExif;

use FriendsOfRedaxo\addon\MediapoolExif\ExifData;
use rex_media;
use rex_sql;

/**
 * Description of Exif
 *
 * @author akrys
 */
class Exif
{
	/** @deprecated use FriendsOfRedaxo\addon\MediapoolExi\Enum\Mode::THROW_EXCEPTION */
	const MODE_THROW_EXCEPTION = 1000; //should be default
	/** @deprecated use FriendsOfRedaxo\addon\MediapoolExi\Enum\Mode::RETURN_NULL */
	const MODE_RETURN_NULL = 1001;
	/** @deprecated use FriendsOfRedaxo\addon\MediapoolExi\Enum\Mode::RETURN_FALSE */
	const MODE_RETURN_FALSE = 1002;
	/** @deprecated use FriendsOfRedaxo\addon\MediapoolExi\Enum\Mode::RETURN_ZERO */
	const MODE_RETURN_ZERO = 1003;
	/** @deprecated use FriendsOfRedaxo\addon\MediapoolExi\Enum\Mode::RETURN_MINUS */
	const MODE_RETURN_MINUS = 1004;
	/** @deprecated use FriendsOfRedaxo\addon\MediapoolExi\Enum\Mode::RETURN_EMPTY_STRING */
	const MODE_RETURN_EMPTY_STRING = 1005;
	/** @deprecated use FriendsOfRedaxo\addon\MediapoolExi\Enum\Mode::RETURN_EMPTY_ARRAY */
	const MODE_RETURN_EMPTY_ARRAY = 1006;
	/** @deprecated use FriendsOfRedaxo\addon\MediapoolExi\Enum\MediaFetchMode::NULL_ONL */
	const GETMEDIA_MODE_NULL_ONLY = 1000;
	/** @deprecated use FriendsOfRedaxo\addon\MediapoolExi\Enum\MediaFetchMode::All */
	const GETMEDIA_MODE_ALL = 1001;

	/**
	 * Alternative Datenerhebung, analog zu rex_media::get()
	 *
	 * @param \FriendsOfRedaxo\addon\MediapoolExif\rex_media $media
	 * @return ExifData
	 */
	public static function get(rex_media $media, /*Enum\Mode*/ $mode = Enum\ReturnMode::THROW_EXCEPTION): ExifData
	{
		return new ExifData($media, $mode);
	}

	/**
	 *
	 * @param type $mode
	 * @return array
	 */
	public static function getMediaToRead(/*Enum\MediaFetchMode*/ $mode = Enum\MediaFetchMode::NULL_ONLY): array
	{
		$rexSQL = rex_sql::factory();

		switch ($mode) {
			case Enum\MediaFetchMode::ALL:
				$sql = 'select filename from rex_media';
				break;
			case Enum\MediaFetchMode::NULL_ONLY:
			default:
				$sql = 'select filename from rex_media where exif is null';
				break;
		}
		return $rexSQL->getArray($sql);
	}
}
