<?php

namespace FriendsOfRedaxo\MediapoolExif\Format;

/**
 * Datei für ...
 *
 * @version       1.0 / 2020-06-12
 * @author        akrys
 */
use FriendsOfRedaxo\MediapoolExif\Enum\Format;

/**
 * Description of FormatBase
 *
 * @author akrys
 * @deprecated Das hier ist kein Interface, es ist eine abstrakte Basis-Klasse. FormatBase ist der bessere Name
 */
abstract class FormatInterface extends FormatBase // remove in v4
{

	/**
	 * Konstruktor
	 * @param array<string, mixed> $data Exif-Daten
	 * @param Format $format Formatierung (deprected)
	 */
	public function __construct(
		array $data,
		/** @deprecated since version 3.1 */ Format $format = Format::READABLE
	) {
		parent::__construct($data, $format);

		$backtrace = debug_backtrace();
		$backtraceText = '';
		$i = 0;
		foreach ($backtrace as $key => $item) {
			if (isset($backtrace[$key]['file']) && isset($backtrace[$key]['line'])) {
				if (stristr($backtrace[$key]['file'], '/mediapool_exif/')) {
					continue;
				}

				$backtraceText = 'in '.$backtrace[$key]['file'].': '.$backtrace[$key]['line'];
				break;
			}
			$i++;
		}

		$msg = FormatInterface::class.' is deprected. Use '.FormatBase::class.' for extension.';
		if ($backtraceText) {
			$msg .= '('.$backtraceText.')';
		}
		user_error($msg, E_USER_DEPRECATED);
	}
}
