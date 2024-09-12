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
interface StandardFormtterInterface extends FormatterInterface
{
	/**
	 * Standard-Formatter
	 *
	 * @param array<string, mixed> $exifData
	 * @return string
	 */
	public function format(array $exifData):string;
}
