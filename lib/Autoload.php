<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */
namespace FriendsOfRedaxo\MediapoolExif;

/**
 * Datei für ...
 *
 * @version       1.0 / 2024-07-21
 * @author        akrys
 */

/**
 * Description of Autoload
 *
 * @author akrys
 */
class Autoload
{

	/**
	 * (Absolutes) Basis Verzeichnis holen
	 * @return string
	 * @deprecated wird nicht mehr benötigt, sobald alte Klassen mit 'addon' im Namespace nicht mehr unterstützt werden
	 * @codeCoverageIgnore
	 */
	public static function getBaseDir() // remove in v4
	{
		return (string) realpath(__DIR__);
	}

	/**
	 * Autoload Funktion
	 * @param string $name
	 * @return boolean
	 *
	 * @deprecated wird nicht mehr benötigt, sobald alte Klassen mit 'addon' im Namespace nicht mehr unterstützt werden
	 * @codeCoverageIgnore
	 */
	public static function autoload($name) // remove in v4
	{
		if (stristr($name, 'FriendsOfRedaxo\\addon\\MediapoolExif')) {
			$oldName = $name;
			$newName = str_replace('FriendsOfRedaxo\\addon\\MediapoolExif', 'FriendsOfRedaxo\\MediapoolExif', $name);

			$backtrace = debug_backtrace();
			$backtraceText = '';
			$i = 0;
			foreach ($backtrace as $key => $item) {
				if (isset($backtrace[$key]['file']) && isset($backtrace[$key]['line'])) {
					if(stristr($backtrace[$key]['file'], '/mediapool_exif/')) {
						continue;
					}

					$backtraceText = ' in '.$backtrace[$key]['file'].': '.$backtrace[$key]['line'];
					break;
				}
				$i++;
			}

			$msg = "Deprecated class name found: ".$oldName.$backtraceText.PHP_EOL.'New class: '.$newName;

			class_alias($newName, $oldName);

			user_error($msg, E_USER_DEPRECATED);
			$name = $newName;
		}


		if (!stristr($name, __NAMESPACE__)) {
			return false; // not a mediapool_exif class
		}

		if (class_exists($name)) {
			return true;
		}

		//namespace parts not in directory structure.
		$name = str_replace(__NAMESPACE__, '', $name);

		$filename = self::getBaseDir().'/'.str_replace('\\', '/', $name).'.php';
		if (file_exists($filename)) {
			require $filename;
			return true;
		}
//		throw new \Exception($filename.' not found');
		return false;
	}
}
