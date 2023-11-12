<?php

/**
 * Datei für ...
 *
 * @version       1.0 / 2020-06-08
 * @author        akrys
 */
namespace FriendsOfRedaxo\addon\MediapoolExif;

use Exception;
use FriendsOfRedaxo\addon\MediapoolExif\Enum\Format;
use FriendsOfRedaxo\addon\MediapoolExif\Enum\ReturnMode;
use FriendsOfRedaxo\addon\MediapoolExif\Exception\NotFoundException;
use FriendsOfRedaxo\addon\MediapoolExif\Format\FormatInterface;
use rex_media;

/**
 * Description of ExifData
 *
 * @author akrys
 */
class ExifData
{
	/**
	 * Media-Objekt
	 *
	 * @todo activate type hint if min PHP-Version > 7.4
	 * @var rex_media
	 */
	private rex_media $media;

	/**
	 * Exif-Daten-Array
	 *
	 * @todo activate type hint if min PHP-Version > 7.4
	 * @var rex_media
	 */
	private array $exif;

	/**
	 * Modus
	 * @var ReturnMode
	 */
	private ReturnMode $mode;

	/**
	 * Konstruktor
	 *
	 * Modus für die Fehlerbehandlung
	 * Standard: MODE_THROW_EXCEPTION
	 *
	 * Wer keine try/catch-Blocke mag, kann sich in dem Fall dann andere false-Werte liefern lassen.
	 *
	 * Die Gefahr, dass Code-Technisch nicht mehr erkannt werden kann, ob es ein Fehler gab oder ob der Wert
	 * tatsächlich 0 oder false oder was auch immer ist, liegt dann natürlich beim jeweiligen Entwickler.
	 * Garantierte Eindeutigkeit gibt es nur im Modus MODE_THROW_EXCEPTION. (Darum auch der Standard)
	 *
	 * <ul>
	 * <li>false (MODE_RETURN_FALSE)</li>
	 * <li>null (MODE_RETURN_NULL)</li>
	 * <li>0 (MODE_RETURN_ZERO)</li>
	 * <li>-1 (MODE_RETURN_MINUS)</li>
	 * <li>'' (MODE_RETURN_EMPTY_STRING)</li>
	 * <li>[] (MODE_RETURN_EMPTY_ARRAY)</li>
	 * </ol>
	 *
	 * @param \FriendsOfRedaxo\addon\MediapoolExif\rex_media $media
	 * @param int $mode
	 */
	public function __construct(rex_media $media, ReturnMode $mode = null)
	{
		$this->media = $media;
		$this->exif = [];

		$exifRaw = $this->media->getValue('exif');
		if ($exifRaw !== null) {
			$this->exif = json_decode($exifRaw, true);
			if (!$this->exif) {
				$this->exif = [];
			}
		}

		if ($mode === null) {
			$mode = ReturnMode::THROW_EXCEPTION;
		}
		$this->mode = $mode;
	}

	/**
	 * Daten holen
	 *
	 * Ist der Index nicht gesetzt, kommt alles in Form eines Arrays zurück.
	 *
	 * @param string $index
	 * @return mixed
	 * @throws NotFoundException
	 */
	public function get(string $index = null): mixed
	{
		if ($index !== null) {
			if (!array_key_exists($index, $this->exif)) {
				return $this->handleExcption(new NotFoundException($index, 'Index not found: '.$index));
			}
			return $this->exif[$index];
		}

		return $this->exif;
	}

	/**
	 * Formatierungsalgorithmus anstoßen
	 * @param string $type
	 * @param Format $format
	 * @param string $className
	 * @return mixed
	 */
	public function format(string $className, Format $format = null): mixed
	{
		try {
			if (!class_exists($className)) {
				//fallback, old call
				$className = '\\FriendsOfRedaxo\\addon\\MediapoolExif\\Format\\'.ucfirst($className);
			}

			return FormatInterface::get($this->exif, $className, $format)->format();
		} catch (Exception $e) {
			return $this->handleExcption($e);
		}
	}

	/**
	 * Fehler-Behandlung
	 *
	 * Welche Rückgabe hätten's gern?
	 *
	 * @param string $exception
	 * @return mixed
	 * @throws NotFoundException
	 */
	private function handleExcption(Exception $exception): mixed
	{
		$return = '';

		switch ($this->mode) {
			case ReturnMode::RETURN_NULL:
				$return = null;
				break;
			case ReturnMode::RETURN_FALSE:
				$return = false;
				break;
			case ReturnMode::RETURN_ZERO:
				$return = 0;
				break;
			case ReturnMode::RETURN_MINUS:
				$return = -1;
				break;
			case ReturnMode::RETURN_EMPTY_STRING:
				$return = '';
				break;
			case ReturnMode::RETURN_EMPTY_ARRAY:
				$return = [];
				break;
			case ReturnMode::THROW_EXCEPTION:
			default:
				throw $exception;
		}
		return $return;
	}
}
