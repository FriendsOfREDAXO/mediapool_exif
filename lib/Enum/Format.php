<?php

/**
 * Datei für ...
 *
 * @author        akrys
 */
namespace FriendsOfRedaxo\MediapoolExif\Enum;

/**
 * Description of Format
 *
 * @author akrys
 * @deprecated since version 3.1. Wird ersatzlos gestrichen. Ein Formatter ist für exakt ein Format zuständig, wenn man die Rohdaten braucht, kann man einen Rohdaten Formatter schreiben.
 */
enum Format: string
{
	case RAW = 'numeric';
	case READABLE = 'readable';
	case UNDEFINED = '';
}
