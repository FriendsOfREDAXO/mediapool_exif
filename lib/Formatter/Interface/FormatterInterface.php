<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPInterface.php to edit this template
 */
namespace FriendsOfRedaxo\MediapoolExif\Formatter\Interface;

/**
 *
 * @author akrys
 */
interface FormatterInterface
{

	/**
	 * Standard-Formatter
	 * @param array<string, mixed> $exifData
	 * @reutrn mixed
	 */
	public function format(array $exifData): mixed;
}
