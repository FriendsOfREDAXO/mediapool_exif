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
