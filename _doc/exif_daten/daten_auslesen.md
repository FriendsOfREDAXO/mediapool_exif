
## Daten aus der Exif-Spalte auslesen

Daten, die in der Spalte ```rex_media.exif``` gespeichert sind, können über folgden Code ausgelesen werden:

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
$exif = Exif::get($media, FriendsOfRedaxo\addon\MediapoolExi\Enum\Mode::RETURN_FALSE);
$index = 'Make';

$vendor = $exif->get($index);
if(!$vendor) {
    echo $index.' nicht da :-(';
} else {
    var_dump($vendor);
}
```

Das hier ist nicht das Standard-Vorgehen, da es u.U. schwierig werden kann, wenn man die Unterscheidung zwischen ```false``` und ```false``` machen muss. In speziellen Fällen kann man es mit ```null``` (```\FriendsOfRedaxo\addon\MediapoolExi\Enum\Mode::RETURN_NULL```) statt ```false```(```\FriendsOfRedaxo\addon\MediapoolExi\Enum\Mode::RETURN_FALSE```) versuchen.
Am Besten aber, man bleibt einfach bei Exceptions. Es ist und bleibt das Eindeutigste.

[weiter mit Formatierung](exif_daten/formatierung.md)


