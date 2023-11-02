<?php

/**
 * Datei für ...
 *
 * @version       1.0 / 2020-06-13
 * @author        akrys
 */
namespace FriendsOfRedaxo\addon\MediapoolExif\Format;

use Exception;
use FriendsOfRedaxo\addon\MediapoolExif\Enum\Format;
use FriendsOfRedaxo\addon\MediapoolExif\Exception\InvalidFormatExcption;
use FriendsOfRedaxo\addon\MediapoolExif\Format\Camera\Aperture;
use FriendsOfRedaxo\addon\MediapoolExif\Format\Camera\Exposure;
use FriendsOfRedaxo\addon\MediapoolExif\Format\Camera\Iso;
use FriendsOfRedaxo\addon\MediapoolExif\Format\Camera\Length;
use FriendsOfRedaxo\addon\MediapoolExif\Format\FormatInterface;

/**
 * Description of Camera
 *
 * @author akrys
 */
class Camera extends FormatInterface
{
	/** @deprecated use \FriendsOfRedaxo\addon\MediapoolExif\Enum\Format::RAW */
	const TYPE_NUMERIC = 'numeric';

	/** @deprecated use \FriendsOfRedaxo\addon\MediapoolExif\Enum\Format::READABLE */
	const TYPE_READABLE = 'readable';

	/**
	 * Daten formatieren
	 * @return array
	 * @throws Exception
	 * @throws InvalidFormatExcption
	 */
	public function format()
	{
		if (!isset($this->data['Make']) && !isset($this->data['Model'])) {
			throw new Exception('No camera data found');
		}

		$format = $this->format;
		if ($format === null) {
			$format = Format::READABLE;
		}

		if (is_callable([$this, $format])) {
			return $this->$format();
		}
		throw new InvalidFormatExcption($format);
	}

	/**
	 * Daten lesbar anzeigen
	 * @return array
	 */
	private function readable()
	{
		return [
			'make' => $this->data['Make'],
			'model' => $this->data['Model'],
			'iso' => (new Iso($this->data, $this->format))->format(),
			'aperture' => (new Aperture($this->data, $this->format))->format(),
			'exposure' => (new Exposure($this->data, $this->format))->format(),
			'length' => (new Length($this->data, $this->format))->format(),
		];
	}

	/**
	 * Daten nummerisch anzeigen
	 * @return array
	 */
	private function numeric()
	{
		return [
			'make' => $this->data['Make'],
			'model' => $this->data['Model'],
			'iso' => (new Iso($this->data, $this->format))->format(),
			'aperture' => (new Aperture($this->data, $this->format))->format(),
			'exposure' => (new Exposure($this->data, $this->format))->format(),
			'length' => (new Length($this->data, $this->format))->format(),
		];
	}
}
