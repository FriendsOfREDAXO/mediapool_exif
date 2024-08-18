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
 * @deprecated since version 3.2. Wird ersatzlos gestrichen. Ein Formatter ist für exakt ein Format zuständig, wenn man die Rohdaten braucht, kann man einen Rohdaten Formatter schreiben.
 */
enum Format: string
{
	case RAW = 'numeric';
	case READABLE = 'readable';
	case UNDEFINED = '';
}
