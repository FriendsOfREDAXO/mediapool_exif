<?php

namespace FriendsOfRedaxo\MediapoolExif\Model;

class GeoCoordinate
{

	/**
	 * Abbildung einer Korrdinate
	 *
	 * @param float $degree Grad
	 * @param float $minute Grad-Minuten
	 * @param float $second Grad-Sekunden
	 * @param string $ref N/S bzw O/W-Referenz
	 */
	public function __construct(
		public readonly float  $degree,
		public readonly float  $minute,
		public readonly float  $second,
		public readonly string $ref,
	){
	}
}
