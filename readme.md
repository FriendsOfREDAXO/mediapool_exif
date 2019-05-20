mediapool_exif
=======================

Das AddOn verwendet den EP ```MEDIA_ADDED```: Wird eine Datei ohne einen Titel hochgeladen, versucht das AddOn, diesen aus dem (Original-)Dateinamen zu extrahieren.

Handelt es sich bei der Datei um ein Bild, so werden die im Bild hinterlegten EXIF-Daten und/oder IPCT-Daten ausgelesen.

Diese werden dann als Titel, bzw. Metainfos hinterlegt (sofern beim Upload keine Daten angegeben wurden). Berücksichtigt werden:

- ```title``` wird mit den EXIF-Werten ```DocumentTitle``` oder ```Headline``` befüllt.
- ```med_description``` wird mit dem EXIF-Wert ```Caption``` befüllt.
- ```med_copyright``` wird mit den EXIF-Werten ```Copyright```, ```Artist```, ```AuthorByLine``` oder ```CaptionWriter``` befüllt.
- ```med_author``` wird mit den EXIF-Werten ```Artist```, ```AuthorByLine``` oder ```CaptionWriter``` befüllt.
- ```med_orientation``` wird mit dem EXIF-Wert ```Orientation``` befüllt.
- ```med_createdate``` wird mit den EXIF-Werten ```FileDateTime```, ```DateTime```,  oder ```CreationDate``` befüllt.
- ```med_keywords``` wird mit dem EXIF-Wert ```Keywords``` befüllt.

Auch der EXIF-Wert ```Subcategories``` wird verwendet, um das Bild ggf. in eine vorhandene Medienkategorie gleichen
Namens einzufügen, sofern beim Upload keine Kategorie definiert wurde.

![Screenshot](https://raw.githubusercontent.com/FriendsOfREDAXO/mediapool_exif/master/assets/screenshot.png)
