mediapool_exif
=======================

Das AddOn verwendet den EP ```MEDIA_ADDED``` und ```MEDIA_UPDATED```. Beim einlesen in den Medienpool werden die EXIF-Daten ermittelt und gespeichert.

Des Weiteren versucht das AddOn, sofern eine Datei ohne Titel hochgeladen wurde, diesen aus dem (Original-)Dateinamen zu extrahieren.

Handelt es sich bei der Datei um ein Bild, so werden die im Bild hinterlegten EXIF-Daten und/oder IPTC-Daten ausgelesen.

Diese werden dann als Titel, bzw. Metainfos hinterlegt (sofern beim Upload keine Daten angegeben wurden). Berücksichtigt werden:

- ```title``` wird mit den EXIF-Werten ```DocumentTitle``` oder ```Headline``` befüllt.
- ```med_description``` wird mit dem EXIF-Wert ```Caption``` befüllt.
- ```med_copyright``` wird mit den EXIF-Werten ```Copyright```, ```Artist```, ```AuthorByLine``` oder ```CaptionWriter``` befüllt.
- ```med_author``` wird mit den EXIF-Werten ```Artist```, ```AuthorByLine``` oder ```CaptionWriter``` befüllt.
- ```med_orientation``` wird mit dem EXIF-Wert ```Orientation``` befüllt.
- ```med_createdate``` wird mit den EXIF-Werten ```FileDateTime```, ```DateTime```,  oder ```CreationDate``` befüllt.
- ```med_keywords``` wird mit dem EXIF-Wert ```Keywords``` befüllt.
- ```med_gps_lat``` *(Beta)* wird mit dem Breitengrad befüllt. (sofern vorhaden)
- ```med_gps_long``` *(Beta)* wird mit dem Längengrad befüllt. (sofern vorhaden)

Auch der EXIF-Wert ```Subcategories``` wird verwendet, um das Bild ggf. in eine vorhandene Medienkategorie gleichen
Namens einzufügen, sofern beim Upload keine Kategorie definiert wurde.

![Screenshot](https://raw.githubusercontent.com/FriendsOfREDAXO/mediapool_exif/master/assets/screenshot.png)


## Beta-Features (Version 1.1)

Es werden alle EXIF-Daten in der Spalte ```rex_media.exif``` gespeichert, so dass man leichten Zugriff erhält.

Im Prinzip sind die Daten über folgenden Code erreichbar:

```php
$media = rex_media::get($filename);
$data = json_decode($media->getValue('exif'), true);
var_dump($data['Make']);
var_dump($data['COMPUTED']['ApertureFNumber']);
```

Damit man sich im Template oder in der Modulausgabe nicht mit ```json_decode``` herumärgern muss, kann man das ganze auch via Exif-Klasse abfragen.

Die Funktion ```get``` operiert dabei nur auf der 1. Ebene des EXIF-Arrays. Man bekommt also einfach den Wert des Array am genannten Index.

```php
use FriendsOfRedaxo\addon\MediapoolExif\Exif;
use FriendsOfRedaxo\addon\MediapoolExif\Exception\NotFoundException;

try {
	$media = rex_media::get($filename);
	$exif = Exif::get($media);
	var_dump($exif->get('Make')); //string
	var_dump($exif->get('COMPUTED')); //array
	var_dump($exif->get(); //komplettes EXIF-Array
} catch (NotFoundException $e) {
	echo $e->getIndex().' nicht da :-(';
}
```

Lässt man den Paramter bei ```$exif->get()``` weg, so bekommt man das ganze Array geliefert. Das kann zu Debug-Zwecken auch mal ganz Hilfreich sein.

Im "Medium bearbeiten" Fenster ist unterhalb des Vorschau-Bildes eine aufklappbare Box angebracht, wo alle EXIF-Daten zum Nachlesen stehen. (Natürlich nur, sofern es für das Bild Daten gibt, sonst wird die Box ausgeblendet.) Dort kann man in der *linken Spalte* die möglichen Parameterwerte für ```$exif->get()``` nachschlagen.


### ohne Exceptions
Der Vollstädigkeit halber sei gesagt, dass man die Funktion ```$exif->get()``` auch so einstellen kann, dass sie keine *Exceptions* wirft. Das ist für den Fall gedacht, dass jemand im Template oder Modul-Ausgabe nicht so gerne mit try/catch-Blöcken arbeitet.

```php
use FriendsOfRedaxo\addon\MediapoolExif\Exif;

$media = rex_media::get($filename);
$exif = Exif::get($media, Exif::MODE_RETURN_FALSE);
$index = 'Make';

$vendor = $exif->get($index);
if(!$vendor) {
    echo $index.' nicht da :-(';
} else {
    var_dump($vendor);
}
```

Das hier ist nicht das Standard-Vorgehen, da es u.U. schwierig werden kann, wenn man die Unterscheidung zwischen ```false``` und ```false``` machen muss. In speziellen Fällen kann man es noch mit ```null``` (```\FriendsOfRedaxo\addon\MediapoolExif\Exif::MODE_RETURN_NULL```) statt ```false```(```\FriendsOfRedaxo\addon\MediapoolExif\Exif::MODE_RETURN_FALSE```) zu versuchen.
Am Besten aber, man bleibt einfach bei Exceptions. Es ist und bleibt das Eindeutigste.

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

In Templates und Modulausgaben kann man analog vorgehen:

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

### JSON-Spielerei in der Datenbank

In MySQL ab Version 5.7.8 und MariaDB ab Version 10.2.3 kann man die JSON-Daten in der ```exif```-Spalte auch zur Filterung verwenden. Leider unterschiedlich je Datenbank, daher "nur" eine Spielerei.

```sql
select exif,
	JSON_VALID(exif) valid,
	/*json_detailed(exif_json) formatted,*/ -- gibt es nicht in MySQL
	exif->"$.FileName" filename,
	exif->"$.Make" make,
	exif->"$.Model" model,
	exif->"$.Make" = 'Apple' isIPhone, -- z.B im where
	exif->"$.COMPUTED.ApertureFNumber" aperture,
	exif->"$.ExposureTime" exposure,
	exif->"$.ISOSpeedRatings" iso
from rex_media;
-- where exif->"$.Make" = 'Apple' 
```

Äquivalent in MariaDB:

```sql
select exif,
	JSON_VALID(exif) valid,
	json_detailed(exif) formatted,
	json_value(exif, '$.FileName') filename,
	json_value(exif,'$.Make') make,
	json_value(exif,'$.Model') model,
	json_value(exif,'$.Make') = 'Apple' isIPhone, -- z.B im where
	json_value(exif,'$.COMPUTED.ApertureFNumber') aperture,
	json_value(exif,'$.ExposureTime') exposure,
	json_value(exif,'$.ISOSpeedRatings') iso
from rex_media
-- where json_value(exif,'$.Make') = 'Apple'
;
```

## Nachträgliches Einlesen

Über die Konsole kann man die Daten für die Bilder, wo noch keine EXIF-Daten in der Datenbank eingelesen wurden, nachträglich einlesen. Das ist z.B. sinnvoll, wenn man das Addon gerade installiert hat und alte Bestandsdaten aktualisieren will.

Man kann auch, sollte man sich mal Daten kaputt gemacht haben, weil man im JSON gespielt hat, das ```exif```-Feld auf ```null``` setzen und das Konsolen-Kommando aufrufen. Im Fall, dass keine Daten in der Datei vorhanden sind, kommt aus ```exif_read_data``` ein leeres Array, so dass die ```exif```-Spalte in dem Fall folgenden Inhalt hat:

```JSON
[]
```

Kommando-Aufruf:

```
redaxo/bin/console mediapool_exif:read
```

Zusätzlich gibt es noch eine Option, die die Daten auch dann einliest, wenn es schon Daten in der Datenbank gibt. z.B. wenn man manuell daran gearbeitet hat, und das JSON-Format kaputt gemacht hat. (Sollte man besser nicht tun)

```
redaxo/bin/console mediapool_exif:read --all
```

Ich übernehme an der Stelle übrigens keine Garantie für überschriebene bzw. kaputte Daten.
