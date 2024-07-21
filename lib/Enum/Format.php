<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */
namespace FriendsOfRedaxo\MediapoolExif\Enum;

/**
 * Datei für ...
 *
 * @version       1.0 / 2023-11-02
 * @author        akrys
 */

/**
 * Description of Format
 *
 * @author akrys
 */
enum Format: string
{
	case RAW = 'numeric';
	case READABLE = 'readable';
	case UNDEFINED = '';
}
