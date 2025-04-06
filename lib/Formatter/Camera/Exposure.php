<?php

/**
 * Datei für ...
 *
 * @author        akrys
 */

namespace FriendsOfRedaxo\MediapoolExif\Formatter\Camera;

use Exception;
use FriendsOfRedaxo\MediapoolExif\Formatter\Interface\StandardFormatterInterface;

/**
 * Description of Exposure
 *
 * @author akrys
 */
class Exposure implements StandardFormatterInterface
{

	/**
	 * Basis-Wert ermitteln.
	 *
	 * Kann in einem abgeleiteten Formatter verwendet werden um den Basis-Wert zu bekommen
	 *
	 * @param array<string, mixed> $exifData
	 * @return string
	 * @throws Exception
	 */
	public function getValue(array $exifData): string
	{
		if (!isset($exifData['ExposureTime'])) {
			throw new Exception('No exposure time found');
		}

		$data = explode('/', $exifData['ExposureTime']);
		if ($this->useNumericalSeconds($data)) {
			$value = number_format((int)$data[0] / (int)$data[1], 1, ',', '.');
			return preg_replace('/,0$/', '', $value) ?? '';
		}
		return $data[0] . '/' . $data[1];
	}

	/**
	 * Daten formatieren
	 * @param array<string, mixed> $exifData
	 * @return string
	 * @throws Exception
	 */
	public function format(array $exifData): string
	{
		return $this->getValue($exifData) . ' s';
	}

	/**
	 * @param list<string> $data
	 * @return bool
	 */
	private function useNumericalSeconds(array $data): bool
	{
		if ($data[0] !== '1') {
			return true;
		}
		if ($data[1] < 3) {
			return true;
		}
		return false;
	}
}
