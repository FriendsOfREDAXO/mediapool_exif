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
	 * Daten formatieren
	 * @param array<string, mixed> $exifData
	 * @return string
	 * @throws Exception
	 */
	public function format(array $exifData): string
	{
		if (!isset($exifData['ExposureTime'])) {
			throw new Exception('No exposure time found');
		}

		$data = explode('/', $exifData['ExposureTime']);
		if ($this->useNumericalSeconds($data)) {
			return preg_replace('/,0$/', '', number_format((int)$data[0] / (int)$data[1], 1, ',', '.')) . ' s';
		}
		return $data[0] . '/' . $data[1] . ' s';
	}

	/**
	 * @param array<int, string> $data
	 * @return bool
	 */
	private function useNumericalSeconds(array $data):bool
	{
		if ($data[0] !== '1') {
			return true;
		}
		if ($data[0] === '1' && $data[1] < 3) {
			return true;
		}
		return false;
	}
}
