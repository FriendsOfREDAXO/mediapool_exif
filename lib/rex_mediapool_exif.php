<?php

class rex_mediapool_exif
{
    protected static $fields = [
        'author' => ['Artist', 'AuthorByLine', 'CaptionWriter'],
        'copyright' => ['Copyright', 'Artist', 'AuthorByLine', 'CaptionWriter'],
        'orientation' => 'Orientation',
        'createdate' => ['FileDateTime', 'DateTime', 'CreationDate'],
        'keywords' => 'Keywords',
        'title' => ['DocumentTitle', 'Headline'],
        'description' => 'Caption',
        'categories' => 'Subcategories',
		'gps'         => 'GPSCoordinates',
    ];

    public static function processUploadedMedia(rex_extension_point $ep)
    {
        if($data = static::getDataByFilename($ep->getParam('filename')))
        {
            $qry = "SELECT * FROM `" . rex::getTablePrefix() . "media` WHERE `filename` = '" . $ep->getParam('filename') . "'";
            $sql = rex_sql::factory();
            $sql->setQuery($qry);
            if($result = $sql->getArray())
            {
                $result = $result[0];
                $update = [];

                // check for category?!
                if(isset($data['categories']))
                {
                    $qry = "SELECT `id` FROM `" . rex::getTablePrefix() . "media_category` WHERE `name` IN ('" . join("', '", $data['categories']) . "') ORDER BY FIELD (`name`, '" . join("', '", $data['categories']) . "') LIMIT 1";
                    $sql->setQuery($qry);
                    if($tmp_result = $sql->getArray())
                    {
                        $data['category_id'] = $tmp_result[0]['id'];
                    }
                    unset($qry, $tmp_result);
                }

                foreach($data as $field => $value)
                {
                    $key = $field;

                    if(in_array('med_'.$field, array_keys($result)))
                    {
                        $key = 'med_' . $field;
                    }

                    if(in_array($key, array_keys($result)))
                    {
                        if(preg_match('/date$/', $key))
                        {
                            $result[$key] = null;
                            $value = date('Y-m-d H:i:s', $value);
                        }
                        else if(is_array($value))
                        {
                            $value = join(', ', $value);
                        }

                        if(empty($result[$key]) && !empty($value))
                        {
                            $update[$field] = "`$key` = ".$sql->escape($value);
                        }
                    }
                }
                unset($field, $value, $key);

                if(!empty($update))
                {
                    $qry = "UPDATE `" . rex::getTablePrefix() . "media` SET " . join(", ", array_values($update)) . " WHERE `filename` = '" . $ep->getParam('filename') . "'";

                    if($sql->setQuery($qry))
                    {
                        $names = '<code>' . join('</code>, <code>', array_keys($update)) . '</code>';
                        $names = preg_replace_callback('/\>[a-z]/', function($match) { return strtoupper($match[0]); }, $names);

                        $ep->setParam('msg', $ep->getParam('msg') . '<br />' . rex_i18n::msg('exif_data_updated') . ' ' . $names);

                        rex_media_cache::delete($ep->getParam('filename'));
                    }
                    unset($qry);
                }
                unset($update);
            }
            unset($result, $qry, $sql);
        }
        unset($data);
    }

    public static function getDataByFilename($filename)
    {
        if($media = rex_media::get($filename))
        {
            if($media->fileExists())
            {
                return static::getData($media);
            }
        }

        return null;
    }

    public static function getData(rex_media $media, $key = null)
    {
        $DATA = array_replace(static::getExifData($media), static::getIptcData($media));
        $return = [];
        foreach(static::$fields as $field => $lookin)
        {
            $lookin = (array) $lookin;
            foreach($lookin as $word)
            {
                if(!empty($DATA[$word]))
                {
                    $value = $DATA[$word];
                    if(preg_match('/date$/', $field))
                    {
                        if(preg_match('/[^0-9]/', $value))
                        {
                            $value = strtotime($value);
                        }
                        else
                        {
                            $value = (int) $value;
                        }
                    }

                    if(!empty($value))
                    {
                        $return[$field] = $value;
                    }
                    unset($value);
                }
            }
            unset($word);
        }

        $return['exif'] = json_encode($DATA);
        unset($DATA, $field, $lookin);

        if(empty($return['title']))
        {
            $return['title'] = $media->getOriginalFileName();

            // extension entfernen
            $return['title'] = preg_replace('/^(.*)\.[\w]{1,}$/', "$1", $return['title']);

            // Unterstriche zu Leerzeichen umwandeln
            $return['title'] = preg_replace('/_/', " ", $return['title']);
            // Doppelte Leerzeichen entfernen
            $return['title'] = trim(preg_replace('/ {2,}/', ' ', $return['title']));

            // Ersten Buchstaben gross schreiben
            $return['title'] = ucfirst($return['title']);

            $return['title'] = trim($return['title']);

            if(empty($return['title']))
            {
                unset($return['title']);
            }
        }

        if(!empty($key) && is_string($key))
        {
            return isset($return[$key]) ? $return[$key] : null;
        }

        return $return;
    }

    protected static function isExifFile(rex_media $media)
    {
        return preg_match('/(\/|\.|^)?(jpe?g|tiff?|wave?)$/i', $media->getType());
    }

    protected static function getExifData(rex_media $media)
    {
        if(static::isExifFile($media))
        {
            $path = rex_path::media($media->getFileName());
            if($exif = exif_read_data($path, 'ANY_TAG'))
            {
				if(isset($exif['GPSLatitude']) && isset($exif['GPSLatitudeRef']) && isset($exif['GPSLongitude']) && isset($exif['GPSLongitudeRef'])) {
					$exif['GPSCoordinates'] = static::convertGPSCoordinates($exif['GPSLatitude'], $exif['GPSLatitudeRef'], $exif['GPSLongitude'], $exif['GPSLongitudeRef']);
				}

                return $exif;
            }
        }
        return [];
    }

    protected static function getIptcDefinitions()
    {
        return [
            '2#005'=>'DocumentTitle',
            '2#010'=>'Urgency',
            '2#015'=>'Category',
            '2#020'=>'Subcategories',
            '2#025'=>'Keywords',
            '2#040'=>'SpecialInstructions',
            '2#055'=>'CreationDate',
            '2#080'=>'AuthorByline',
            '2#085'=>'AuthorTitle',
            '2#090'=>'City',
            '2#095'=>'State',
            '2#101'=>'Country',
            '2#103'=>'OTR',
            '2#105'=>'Headline',
            '2#110'=>'Source',
            '2#115'=>'PhotoSource',
            '2#116'=>'Copyright',
            '2#120'=>'Caption',
            '2#122'=>'CaptionWriter'
        ];
    }

    protected static function getIptcData(rex_media $media)
    {
        $return = [];

        if(static::isExifFile($media))
        {
            $path = rex_path::media($media->getFileName());
            if($size = getimagesize($path, $info))
            {
                if(isset($info['APP13']))
                {
                    if($iptc = iptcparse($info['APP13']))
                    {
                        foreach(static::getIptcDefinitions() as $code => $label)
                        {
                            if(!empty($iptc[$code]))
                            {
                                $return[$label] = count($iptc[$code]) == 1 ? $iptc[$code][0] : $iptc[$code];
                            }
                        }
                        unset($code, $label);
                    }
                    unset($iptc);
                }
            }
        }
        unset($path, $size, $info);

        return $return;
    }

    public static function mediapoolDetailOutput (rex_extension_point $ep): string
    {
        $subject = $ep->getSubject();

        $exif = json_decode($ep->getParam('media')->getValue('exif'), 1);
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

    protected static function mediapoolDetailOutputLine(array $exif): string
    {
        $lines = [];
        foreach ($exif as $key => $value) {
            if (is_array($value)) {
                $lines[] = [
                    'key' => $key,
                    'value' => self::mediapoolDetailOutputLine($value),
                ];
            } else {
                $lines[] = [
                    'key' => $key,
                    'value' => $value,
                ];
            }
        }

        $fragment = new rex_fragment([
            'exif' => $lines,
        ]);
        $return = $fragment->parse('fragments/mediapool_sidebar_line.php');

        return $return;
    }

	protected static function convertGPSCoordinates($GPSLatitude, $GPSLatitude_Ref, $GPSLongitude, $GPSLongitude_Ref)
	{
		$GPSLatfaktor  = 1;
		$GPSLongfaktor = 1;

		if($GPSLatitude_Ref == 'S') $GPSLatfaktor = -1;
		if($GPSLongitude_Ref == 'W') $GPSLongfaktor = -1;

		$GPSLatitude_h = explode("/", $GPSLatitude[0]);
		$GPSLatitude_m = explode("/", $GPSLatitude[1]);
		$GPSLatitude_s = explode("/", $GPSLatitude[2]);

		$GPSLat_h = $GPSLatitude_h[0] / $GPSLatitude_h[1];
		$GPSLat_m = $GPSLatitude_m[0] / $GPSLatitude_m[1];
		$GPSLat_s = $GPSLatitude_s[0] / $GPSLatitude_s[1];

		$GPSLatGrad = $GPSLatfaktor * ($GPSLat_h + ($GPSLat_m + ($GPSLat_s / 60)) / 60);

		$GPSLongitude_h = explode("/", $GPSLongitude[0]);
		$GPSLongitude_m = explode("/", $GPSLongitude[1]);
		$GPSLongitude_s = explode("/", $GPSLongitude[2]);
		$GPSLong_h      = $GPSLongitude_h[0] / $GPSLongitude_h[1];
		$GPSLong_m      = $GPSLongitude_m[0] / $GPSLongitude_m[1];
		$GPSLong_s      = $GPSLongitude_s[0] / $GPSLongitude_s[1];
		$GPSLongGrad    = $GPSLongfaktor * ($GPSLong_h + ($GPSLong_m + ($GPSLong_s / 60)) / 60);

		return number_format($GPSLatGrad, 6,'.','') . ',' . number_format($GPSLongGrad, 6,'.','');
	}

    public function readExifFromFile($filename): void
    {
        $subject = null;
        $params = [
            'filename' =>$filename,
        ];
        $ep = new rex_extension_point('dummy', $subject, $params, false);
        rex_mediapool_exif::processUploadedMedia($ep);
    }
}
