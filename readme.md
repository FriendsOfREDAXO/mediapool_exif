mediapool_exif
=======================

Das AddOn dient hängt sich in den EP ```MEDIA_ADDED``` ein. Wird eine Datei hochgeladen und es wird kein Titel dazu
angegeben, versucht das AddOn diesen aus dem (Original-)Dateinamen zu extrahieren.

Handelt es sich bei der Datei um ein Bild, so werden die im Bild hinterlegten EXIF-Daten und/oder IPCT-Daten ausgelesen.
Diese werden dann als Titel, bzw. Metainfos hinterlegt (sofern beim Upload keine Daten angegeben wurden). Die Zuordnung erfolgt folgendermaßen:

- ```title``` wird mit den EXIF-Werten ```Headline``` oder ```DocumentTitle``` befüllt.
- ```med_description``` wird mit den EXIF-Wert ```Caption``` befüllt.
- ```med_author``` wird mit den EXIF-Werten ```Artist```, ```AuthorByLine``` oder ```CaptionWriter``` befüllt.
- ```med_copyright``` wird mit den EXIF-Wert ```Copyright``` befüllt.
- ```med_orientation``` wird mit den EXIF-Wert ```Orientation``` befüllt.
- ```med_createdate``` wird mit den EXIF-Werten ```FileDateTime```, ```DateTime```,  oder ```CreationDate``` befüllt.
- ```med_keywords``` wird mit den EXIF-Wert ```Keywords``` befüllt.

Auch das der EXIF-Wert ```Subcategories``` wird verwendet, um das Bild ggf. in eine vorhandene Medienkategorie gleichen
Namens einzufügen (sofern beim Upload keine Kategorie definiert wurde).
