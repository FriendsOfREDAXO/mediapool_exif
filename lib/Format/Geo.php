<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace FriendsOfRedaxo\addon\MediapoolExif\Format;

/**
 * Datei für ...
 *
 * @version       1.0 / 2020-06-12
 * @author        akrys
 */

/**
 * Description of Geo
 *
 * @author akrys
 */
class Geo
	extends FormatInterface
{

	/**
	 * Formatierung der Daten
	 * @return array
	 * @throws \Exception
	 */
	public function format()
	{
		if (
			!isset($this->data['GPSLatitude']) ||
			!isset($this->data['GPSLatitudeRef']) ||
			!isset($this->data['GPSLongitude']) ||
			!isset($this->data['GPSLongitudeRef'])) {
			throw new \Exception('GPS not found');
		}

		$GPSLatitude = $this->data['GPSLatitude'];
		$GPSLatitude_Ref = $this->data['GPSLatitudeRef'];
		$GPSLongitude = $this->data['GPSLongitude'];
		$GPSLongitude_Ref = $this->data['GPSLongitudeRef'];

		$GPSLatfaktor = 1; //N
		if ($GPSLatitude_Ref == 'S') {
			$GPSLatfaktor = -1;
		}

		$GPSLongfaktor = 1; //E
		if ($GPSLongitude_Ref == 'W') {
			$GPSLongfaktor = -1;
		}

		$GPSLatitude_h = explode("/", $GPSLatitude[0]);
		$GPSLatitude_m = explode("/", $GPSLatitude[1]);
		$GPSLatitude_s = explode("/", $GPSLatitude[2]);

		$GPSLat_h = $GPSLatitude_h[0] / $GPSLatitude_h[1];
		$GPSLat_m = $GPSLatitude_m[0] / $GPSLatitude_m[1];
		$GPSLat_s = $GPSLatitude_s[0] / $GPSLatitude_s[1];

		$GPSLatGrad = $GPSLatfaktor * ($GPSLat_h + ($GPSLat_m + ($GPSLat_s / 60)) / 60);

		$GPSLongitude_h = explode("/", $GPSLongitude[0]);
		$GPSLongitude_m = explode("/", $GPSLongitude[1]);
		$GPSLongitude_s = explode("/", $GPSLongitude[2]);
		$GPSLong_h = $GPSLongitude_h[0] / $GPSLongitude_h[1];
		$GPSLong_m = $GPSLongitude_m[0] / $GPSLongitude_m[1];
		$GPSLong_s = $GPSLongitude_s[0] / $GPSLongitude_s[1];
		$GPSLongGrad = $GPSLongfaktor * ($GPSLong_h + ($GPSLong_m + ($GPSLong_s / 60)) / 60);

		return [
			'lat' => number_format($GPSLatGrad, 6, '.', ''),
			'long' => number_format($GPSLongGrad, 6, '.', ''),
		];
	}
}
