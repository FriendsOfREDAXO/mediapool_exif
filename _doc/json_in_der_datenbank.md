
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
