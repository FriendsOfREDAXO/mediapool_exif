<?php

/**
 * Datei für ...
 *
 * @version       1.0 / 2020-06-13
 * @author        akrys
 */
namespace FriendsOfRedaxo\MediapoolExif\Format;

use Exception;
use FriendsOfRedaxo\MediapoolExif\Enum\Format;
use FriendsOfRedaxo\MediapoolExif\Exception\InvalidFormatExcption;
use FriendsOfRedaxo\MediapoolExif\Format\Camera\Aperture;
use FriendsOfRedaxo\MediapoolExif\Format\Camera\Exposure;
use FriendsOfRedaxo\MediapoolExif\Format\Camera\Iso;
use FriendsOfRedaxo\MediapoolExif\Format\Camera\Length;
use FriendsOfRedaxo\MediapoolExif\Format\FormatInterface;

/**
 * Description of Camera
 *
 * @author akrys
 */
class Camera extends FormatInterface
{

	/**
	 * Daten formatieren
	 * @return array<string, mixed>
	 * @throws Exception
	 * @throws InvalidFormatExcption
	 */
	public function format(): array
	{
		if (!isset($this->data['Make']) && !isset($this->data['Model'])) {
			throw new Exception('No camera data found');
		}

		if ($this->format === null) {
			$this->format = Format::READABLE;
		}
		$formatValue = $this->format->value;

		/** @phpstan-ignore-next-line */
		if (!is_callable([$this, $formatValue])) {
			// @codeCoverageIgnoreStart
			throw new InvalidFormatExcption($this->format);
			// @codeCoverageIgnoreEnd
		}

		return $this->$formatValue();
	}

	/**
	 * Daten lesbar anzeigen
	 * @return array<string, mixed>
	 * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
	 * -> zugriff via $this->$formatValue
	 */
	private function readable(): array
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
	 * @return array<string, mixed>
	 * @SuppressWarnings(PHPMD.UnusedPrivateMethod)
	 * -> zugriff via $this->$formatValue
	 */
	private function numeric(): array
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
