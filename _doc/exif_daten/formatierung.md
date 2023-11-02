
### Formatierung

Da die Daten nicht immer in einem nutzbaren Format eingetragen sind, gibt es neben ```EXIF::get``` für die Rohdaten auch ```EXIF::format()```, wo man noch eine Aufbereitung dazwischen schalten kann.

#### Exkurs: Was passiert da genau?

Eigentlich ist es nur ein Interface, wo die Methode ```format()``` implementiert werden muss. Das "Interface" (eigentlich eine abstrakte Klasse) kann in ```FriendsOfRedaxo\addon\MediapoolExif\Format\FormatInterface``` eingesehen werden.

Da für das Einlesen der Geo-Daten direkt der Ausgabe-Formatter benutzt wird, kann man die konkrete Nutzung in der Methode ```rex_mediapool_exif::getExifData()``` nachvollziehen.
(```rex_mediapool_exif``` habe ich noch nicht dem Namespace zugeordnet. Kommt frühestens in Version 2.0 Breaking Changes und so…)

```php
use FriendsOfRedaxo\addon\MediapoolExif\Format\FormatInterface;

try {
	$coordinates = FormatInterface::get($exif, 'Geo')->format();
	$exif['GPSCoordinatesLat'] = $coordinates['lat'];
	$exif['GPSCoordinatesLong'] = $coordinates['long'];
} catch (Exception $e) {
	//no GPS Data, nothing to to
}
```

#### In Modulen und Templates

Der Aufruf von ```FormatInterface::get()``` ist zur einfacheren Nutzung in die Funktion ```Exif::format()``` gekapselt. Somit kann man das oben genutzte Beispiel zum Formatieren der Geo-Daten in Templates und Modulausgaben einfacher bzw. kürzer Formulieren: ```$exif->format('Geo');```

```php
use FriendsOfRedaxo\addon\MediapoolExif\Exif;

$media = rex_media::get($filename);
$exif = Exif::get($media);
try {
	var_dump($exif->format('Geo'))
} catch (InvalidFormatExcption $e) {
	echo $e->getFormat().' unbekannt.';
} catch (Exception $e) {
	var_dump($e->getMessage());
}

```
Ausgabe:

```
rex:///template/1:39:
array (size=2)
  'lat' => string '51.060089' (length=9)
  'long' => string '7.122339' (length=8)
```

#### Format-Parameter

Als Beispiel, wie der ```format```-Parameter genutzt werden kann, habe ich mal beispielhaft einen Formatter für die Kamera-Daten geschrieben. Intern macht der Formatter gebrauch von weiteren Formattern, so dass man diese Teilerhebung der Daten auch direkt im Template oder ineiner Modulausgabe verwenden kann.

```php
use FriendsOfRedaxo\addon\MediapoolExif\Format\Camera;

$media = rex_media::get($filename);
$exif = Exif::get($media);

try {
	print 'Camera numeric<br />';
	var_dump($exif->format('Camera', Camera::TYPE_NUMERIC));

	print 'Camera readable<br />';
	var_dump($exif->format('Camera', Camera::TYPE_READABLE));

	print 'interner Length Formatter';
	var_dump($exif->format('Camera\\Length', Camera::TYPE_READABLE));
} catch (InvalidFormatExcption $e) {
	echo $e->getFormat().' unbekannt.';
} catch (Exception $e) {
	var_dump($e->getMessage());
}

```

Ausgabe:

```
Camera numeric
rex:///template/1:34:
array (size=6)
  'make' => string 'Canon' (length=5)
  'model' => string 'Canon EOS 80D' (length=13)
  'iso' => int 100
  'aperture' => string '5.6' (length=3)
  'exposure' => float 0.016666666666667
  'length' => float 49

Camera readable
rex:///template/1:36:
array (size=6)
  'make' => string 'Canon' (length=5)
  'model' => string 'Canon EOS 80D' (length=13)
  'iso' => int 100
  'aperture' => string 'f/5.6' (length=5)
  'exposure' => string '1/60 s' (length=6)
  'length' => string '49 mm' (length=5)

interner Length Formatter
rex:///template/1:39:string '49 mm' (length=5)
```

[weiter mit JSON in der Datenbank](../json_in_der_datenbank.md)