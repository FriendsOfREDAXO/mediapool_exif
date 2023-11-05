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

Ab Version 2.0 werden alle EXIF-Daten in der Spalte ```rex_media.exif``` als ```JSON```-Array gespeichert, so dass man leichten Zugriff erhält.

Damit die Dokumentation hier nicht weiter ausufert, liegen alle weiteren dokumentieren Sachverhalte im [Wiki](https://github.com/FriendsOfREDAXO/mediapool_exif/wiki) bereit:
[https://github.com/FriendsOfREDAXO/mediapool_exif/wiki](https://github.com/FriendsOfREDAXO/mediapool_exif/wiki)