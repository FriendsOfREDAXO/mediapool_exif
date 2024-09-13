<?php

/**
 * Datei für ...
 *
 * @author        akrys
 */
namespace FriendsOfRedaxo\MediapoolExif\Enum;

/**
 * Description of Mode
 *
 * @author akrys
 */
enum ReturnMode: int
{
	case THROW_EXCEPTION = 1000; //should be default
	case RETURN_NULL = 1001;
	case RETURN_FALSE = 1002;
	case RETURN_ZERO = 1003;
	case RETURN_MINUS = 1004;
	case RETURN_EMPTY_STRING = 1005;
	case RETURN_EMPTY_ARRAY = 1006;
}
