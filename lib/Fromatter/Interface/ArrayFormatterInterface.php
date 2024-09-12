<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPInterface.php to edit this template
 */
namespace FriendsOfRedaxo\MediapoolExif\Interface\Formatter;

/**
 *
 * @author akrys
 */
interface ArrayFormatterInterface extends FormatterInterface
{

	/**
	 * Formatter, der ein Array liefert.
	 *
	 * z.B. für Geo-Daten
	 *
	 * @param array<string, mixed> $exifData
	 * @return array<string, mixed>
	 */
	public function format(array $exifData): array;
}
