<?php

namespace FriendsOfRedaxo\MediapoolExif;

use Exception;
use FriendsOfRedaxo\MediapoolExif\Enum\IptcDefinitions;
use FriendsOfRedaxo\MediapoolExif\Exception\IptcException;
use FriendsOfRedaxo\MediapoolExif\Formatter\Geo;
use rex;
use rex_extension_point;
use rex_fragment;
use rex_i18n;
use rex_logger;
use rex_media;
use rex_media_cache;
use rex_path;
use rex_sql;
use function mb_convert_encoding;

/**
 * @codeCoverageIgnore
 * -> Nur Redaxo=Plattform-Integration
 * @SuppressWarnings("PHPMD.ExcessiveClassComplexity")
 * @SuppressWarnings("PHPMD.CouplingBetweenObjects")
 * -> man könnte vllt mal was auslagern
 */
final class MediapoolExif
{
	/**
	 * Field mapping
	 * @var array<string, mixed>
	 */
	protected static array $fields = [
		'author' => ['Artist', 'AuthorByLine', 'CaptionWriter'],
		'copyright' => ['Copyright', 'Artist', 'AuthorByLine', 'CaptionWriter'],
		'orientation' => 'Orientation',
		'createdate' => ['FileDateTime', 'DateTime', 'CreationDate'],
		'keywords' => 'Keywords',
		'title' => ['DocumentTitle', 'Headline'],
		'description' => 'Caption',
		'categories' => 'Subcategories',
		'gps_lat' => 'GPSCoordinatesLat',
		'gps_long' => 'GPSCoordinatesLong',
	];

	/**
	 * Upload processing
	 * @param rex_extension_point<string> $exp
	 * @SuppressWarnings("PHPMD.ElseExpression")
	 * -> zu tief verschachtelt.... vllt. Funktionsauslagerung?
	 * @SuppressWarnings("PHPMD.IfStatementAssignment")
	 * -> bei Datenbankabfragen kaum anders zu machen
	 * @SuppressWarnings("PHPMD.CyclomaticComplexity")
	 * @SuppressWarnings("PHPMD.NPathComplexity")
	 */
	public static function processUploadedMedia(rex_extension_point $exp): void
	{
		$oldMedia = rex_media::get($exp->getParam('filename'));
		if ($data = static::getDataByFilename($exp->getParam('filename'))) {
			$qry = "SELECT * FROM `".rex::getTablePrefix()."media` WHERE `filename` = '".$exp->getParam('filename')."'";
			$sql = rex_sql::factory();
			$sql->setQuery($qry);
			if ($result = $sql->getArray()) {
				$result = $result[0];

				$update = [];

				// check for category?!
				if (isset($data['categories'])) {
					$qry = "SELECT `id` FROM `".rex::getTablePrefix()."media_category` WHERE ".
						"`name` IN ('".join("', '", $data['categories'])."') ".
						"ORDER BY FIELD (`name`, '".join("', '", $data['categories'])."') LIMIT 1";
					$sql->setQuery($qry);
					if ($tmp_result = $sql->getArray()) {
						$data['category_id'] = $tmp_result[0]['id'];
					}
					unset($qry, $tmp_result);
				}

				foreach ($data as $field => $value) {
					$key = $field;

					if (in_array('med_'.$field, array_keys($result))) {
						$key = 'med_'.$field;
					}

					if (in_array($key, array_keys($result))) {
						if (preg_match('/date$/', $key)) {
							$result[$key] = null;
							$value = date('Y-m-d H:i:s', $value);
						} elseif (is_array($value)) {
							$value = join(', ', $value);
						}

						if (empty($result[$key]) && !empty($value)) {
							$update[$field] = "`$key` = ".$sql->escape($value);
						}
					}
				}
				unset($field, $value, $key);

				if (self::exifHasChanged($data['exif'], $oldMedia?->getValue('exif'))) {
					$update['exif'] = '`exif`='.$sql->escape($data['exif']);
				}

				if (!empty($update)) {
					$qry = "UPDATE `".rex::getTablePrefix()."media` ".
						"SET ".join(", ", array_values($update))." ".
						"WHERE `filename` = '".$exp->getParam('filename')."'";

					/** @phpstan-ignore-next-line */
					if ($sql->setQuery($qry)) {
						$names = '<code>'.join('</code>, <code>', array_keys($update)).'</code>';
						$names = preg_replace_callback(
							'/\>[a-z]/', function ($match) {
								return strtoupper($match[0]);
							}, $names
						);

						$msg = $exp->getParam('msg').'<br />'.rex_i18n::msg('exif_data_updated').' '.$names;
						$exp->setParam('msg', $msg);

						rex_media_cache::delete($exp->getParam('filename'));
					} else {
						rex_logger::factory()->alert('SQL-Error ['.$sql->getErrno().'] '.$sql->getError());
					}
					unset($qry);
				}
				unset($update);
			}
			unset($result, $qry, $sql);
		}
		unset($data);
	}

	/**
	 * Check ob die EXIF-Daten sich geändert haben.
	 *
	 * @param string $new
	 * @param string|int|null $old
	 * @return bool
	 */
	private static function exifHasChanged(string $new, string|int|null $old): bool
	{
		$newArray = json_decode($new, true);
		$oldArray = [];
		if ($old !== null) {
			$oldArray = json_decode((string) $old, true);
		}

		/**
		 * FileDateTime ändert sich immer.
		 * Hieße: wir ändern das exif-Feld, obwohl sich nichts relevantes geändert hat.
		 */
		unset($newArray['FileDateTime'], $oldArray['FileDateTime']);

		$newString = json_encode($newArray);
		$oldString = json_encode($oldArray);

		return $newString !== $oldString;
	}

	/**
	 * Daten au der Datei holen
	 * @param string $filename
	 * @return array<string, mixed>
	 */
	public static function getDataByFilename(string $filename): array
	{
		$media = rex_media::get($filename);
		if ($media) {
			if ($media->fileExists()) {
				return static::getData($media);
			}
		}

		return [];
	}

	/**
	 * Daten aus der Datei verarbeiten
	 * @param rex_media $media
	 * @param string $key
	 * @return mixed
	 * @SuppressWarnings("PHPMD.ElseExpression")
	 * -> zu tief verschachtelt.... vllt. Funktionsauslagerung?
	 * @SuppressWarnings("PHPMD.CyclomaticComplexity")
	 * @SuppressWarnings("PHPMD.NPathComplexity")
	 */
	public static function getData(rex_media $media, string $key = null): mixed
	{
		$DATA = array_replace(static::getExifData($media), static::getIptcData($media));
		$return = [];
		foreach (static::$fields as $field => $lookin) {
			$lookin = (array) $lookin;
			foreach ($lookin as $word) {
				if (!empty($DATA[$word])) {
					$value = $DATA[$word];
					if (preg_match('/date$/', $field)) {
						if (preg_match('/[^0-9]/', $value)) {
							$value = strtotime($value);
						} else {
							$value = (int) $value;
						}
					}

					if (!empty($value)) {
						$return[$field] = $value;
					}
					unset($value);
				}
			}
			unset($word);
		}

		$return['exif'] = json_encode($DATA);
		if (!$return['exif']) {
			\rex_logger::factory()->alert((string) json_last_error());
		}
		unset($DATA, $field, $lookin);

		if (empty($return['title'])) {
			$return['title'] = $media->getOriginalFileName();

			// extension entfernen
			$return['title'] = preg_replace('/^(.*)\.[\w]{1,}$/', "$1", $return['title']);

			// Unterstriche zu Leerzeichen umwandeln
			$return['title'] = preg_replace('/_/', " ", (string) $return['title']);
			// Doppelte Leerzeichen entfernen
			$return['title'] = trim((string) preg_replace('/ {2,}/', ' ', (string) $return['title']));

			// Ersten Buchstaben gross schreiben
			$return['title'] = ucfirst((string) $return['title']);

			$return['title'] = trim((string) $return['title']);

			if (empty($return['title'])) {
				unset($return['title']);
			}
		}

		if (!empty($key)) {
			return isset($return[$key]) ? $return[$key] : null;
		}

		return $return;
	}

	/**
	 * Dateityp-Prüfung.
	 * Beantwortet die Frage, ob die Datei Datei EXIF-Daten enthalten kann
	 * @param rex_media $media
	 * @return bool
	 */
	protected static function isExifFile(rex_media $media): bool
	{
		return preg_match('/(\/|\.|^)?(jpe?g|tiff?|wave?)$/i', $media->getType()) > 0;
	}

	/**
	 * EXIF-Daten aus dem rex_media-Objekt holen.
	 * @param rex_media $media
	 * @return array<string, mixed>
	 */
	protected static function getExifData(rex_media $media): array
	{
		if (static::isExifFile($media)) {
			$path = rex_path::media($media->getFileName());
			$exif = exif_read_data($path, 'ANY_TAG');

			if ($exif) {
				// Bugfix json_encode error 5
				// 5 = JSON_ERROR_UTF8 => alles als UTF8 markieren.
				foreach ($exif as $key => $value) {
					if (is_string($value)) {
						$exif[$key] = mb_convert_encoding($value, 'UTF-8');
					}
				}

				try {
					$coordinates = (new Geo())->format($exif);
					$exif['GPSCoordinatesLat'] = $coordinates['lat'] ?? 0;
					$exif['GPSCoordinatesLong'] = $coordinates['long'] ?? 0;
				} catch (Exception $e) {
					//no GPS Data, nothing to to
				}

				return $exif;
			}
		}
		return [];
	}

	/**
	 * IPTC-Daten holen
	 * @param rex_media $media
	 * @return array<string, mixed>
	 */
	protected static function getIptcData(rex_media $media): array
	{
		$return = [];

		if (!static::isExifFile($media)) {
			return $return;
		}

		try {
			$iptc = static::parseIptc($media);
			foreach (IptcDefinitions::cases() as $case) {
				if (!empty($iptc[$case->getCode()])) {
					$count = count($iptc[$case->getCode()]) == 1 ? $iptc[$case->getCode()][0] : $iptc[$case->getCode()];
					$return[$case->getLabel()] = $count;
				}
			}
		} catch (IptcException $e) {
			return $return;
		}
		return $return;
	}

	/**
	 * IPTC-Daten parsen.
	 *
	 * @param rex_media $media
	 * @return array<string, mixed>
	 * @throws IptcException
	 */
	private static function parseIptc(rex_media $media): array
	{
		$iptc = [];
		$path = rex_path::media($media->getFileName());
		$size = getimagesize($path, $info);
		if (!$size) {
			throw new IptcException('no size');
		}

		if (isset($info['APP13'])) {
			$iptc = iptcparse($info['APP13']);
		}

		if (!$iptc) {
			throw new IptcException('no iptc');
		}
		return $iptc;
	}

	/**
	 * Seitenleiste für die Medienpool-Detailseite generieren
	 * @param rex_extension_point<string> $exp
	 * @return string
	 */
	public static function mediapoolDetailOutput(rex_extension_point $exp): string
	{
		$subject = $exp->getSubject();

		$exifRaw = $exp->getParam('media')->getValue('exif');
		if ($exifRaw === null) {
			return $subject;
		}

		$exif = json_decode($exifRaw, true);
		if ($exif) {
			$lines = '';
			//rekursiver Aufruf einer anonymen Funktion
			$lines .= self::mediapoolDetailOutputLine($exif);

			$fragment = new rex_fragment([
				'collapsed' => true,
				'title' => 'EXIF',
				'lines' => $lines,
			]);
			$subject .= $fragment->parse('fragments/mediapool_sidebar.php');
		}
		return $subject;
	}

	/**
	 * Einzelzeile der Medienpool-Detail-Ausgabe verarbeiten.
	 * @param array<string, mixed> $exif
	 * @return string
	 */
	protected static function mediapoolDetailOutputLine(array $exif): string
	{
		$lines = [];
		foreach ($exif as $key => $value) {
			$line = [
				'key' => $key,
				'value' => $value,
			];
			if (is_array($value)) {
				$line = [
					'key' => $key,
					'value' => self::mediapoolDetailOutputLine($value),
				];
			}
			$lines[] = $line;
		}

		$fragment = new rex_fragment([
			'exif' => $lines,
		]);
		$return = $fragment->parse('fragments/mediapool_sidebar_line.php');

		return $return;
	}

	/**
	 * EXIF-Daten außerhalb des Upload-Prozesses ermitteln.
	 *
	 * Die Funktion wird z.B. im Cron verwendet.
	 *
	 * @param string $filename
	 * @return void
	 */
	public static function readExifFromFile(string $filename): void
	{
		$subject = null;
		$params = [
			'filename' => $filename,
		];

		/**
		 * @var rex_extension_point<string> $exp
		 */
		$exp = new rex_extension_point('dummy', $subject, $params, false);
		MediapoolExif::processUploadedMedia($exp);
	}
}
