<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace FriendsOfRedaxo\addon\MediapoolExif\Enum;

/**
 * Datei für ...
 *
 * @version       1.0 / 2023-11-02
 * @author        akrys
 */

/**
 * Description of Mode
 *
 * @author akrys
 */
class /*enum*/ ReturnMode /*:int*/
{
	const /*case*/ THROW_EXCEPTION = 1000; //should be default
	const /*case*/ RETURN_NULL = 1001;
	const /*case*/ RETURN_FALSE = 1002;
	const /*case*/ RETURN_ZERO = 1003;
	const /*case*/ RETURN_MINUS = 1004;
	const /*case*/ RETURN_EMPTY_STRING = 1005;
	const /*case*/ RETURN_EMPTY_ARRAY = 1006;
}
