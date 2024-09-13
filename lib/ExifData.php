<?php

/**
 * Datei für ...
 *
 * @author        akrys
 */
namespace FriendsOfRedaxo\MediapoolExif;

use Exception;
use FriendsOfRedaxo\MediapoolExif\Enum\Format;
use FriendsOfRedaxo\MediapoolExif\Enum\ReturnMode;
use FriendsOfRedaxo\MediapoolExif\Exception\InvalidClassException;
use FriendsOfRedaxo\MediapoolExif\Exception\NotFoundException;
use FriendsOfRedaxo\MediapoolExif\Format\FormatBase;
use FriendsOfRedaxo\MediapoolExif\Format\FormatInterface;
use FriendsOfRedaxo\MediapoolExif\Formatter\Interface\FormatterInterface;
use rex_media;

/**
 * Description of ExifData
 *
 * @author akrys
 */
class ExifData
{
	/**
	 * Exif-Daten-Array
	 *
	 * @var array<string, mixed>
	 */
	private array $exif;

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
	 * @param rex_media $media
	 * @param ReturnMode $mode
	 */
	public function __construct(
		private rex_media $media, private ?ReturnMode $mode = null
	) {
		$this->exif = [];

		$exifRaw = $this->media->getValue('exif');
		if ($exifRaw !== null) {
			$this->exif = json_decode((string) $exifRaw, true);
			if (!$this->exif) {
				$this->exif = [];
			}
		}

		if ($this->mode === null) {
			$this->mode = ReturnMode::THROW_EXCEPTION;
		}
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
	 * @param string|FormatterInterface $objectParam
	 * @param Format $format
	 * @return mixed
	 */
	public function format(
		string|FormatterInterface $objectParam,
		/** @deprecated since version 3.1 */ Format $format = Format::READABLE
	): mixed {
		try {
			/** @var FormatterInterface $object */
			$object = null;
			/** @var string $className */
			$className = null;

			if (is_object($objectParam)) {
				$object = $objectParam;
				$className = get_class($object);
			}

			if (is_string($objectParam)) {
				$className = $objectParam;
				if (!class_exists($objectParam)) {
					//fallback, old call internal formatter without namespace
					$className = '\\FriendsOfRedaxo\\MediapoolExif\\Format\\'.ucfirst($objectParam);
				}
			}

//			print $className;
//			print '<pre>';
//			var_dump([
//				'classname' => $className,
//				'class_parents' => class_parents($className, true),
//				'class_implements' => class_implements($className, true),
//			]);
//			print '</pre>';

			if(!class_exists($className)) {
				//phpdoc warnings class_parents() with not existing class names
				throw new InvalidClassException($className);
			}

			// @codeCoverageIgnoreStart
			// deprected
			if (isset(class_parents($className)[FormatInterface::class])) {
				return FormatInterface::get($this->exif, $className, $format)->format();
			}
			// @codeCoverageIgnoreEnd

			if (isset(class_parents($className)[FormatBase::class])) {
				return FormatBase::get($this->exif, $className)->format();
			}

			if (isset(class_implements($className)[FormatterInterface::class])) {
				$object = new $className();
				if (is_a($object, FormatterInterface::class)) {
					return $object->format($this->exif);
				}
			}

			throw new InvalidClassException($className);
		} catch (Exception $e) {
			return $this->handleExcption($e);
		}
	}

	/**
	 * Fehler-Behandlung
	 *
	 * Welche Rückgabe hätten's gern?
	 *
	 * @param Exception $exception
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
