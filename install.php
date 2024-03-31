<?php

rex_sql_table::get(rex::getTable('media'))
    ->ensurePrimaryIdColumn()
    ->ensureColumn(new rex_sql_column('exif', 'longtext', true))
    ->ensure();

rex_metainfo_add_field('Beschreibung', 'med_description', 1, '', 2, '', '', '', '');
rex_metainfo_add_field('Schlagworte', 'med_keywords', 2, '', 1, '', '', '', '');
rex_metainfo_add_field('Copyright', 'med_copyright', 3, '', 1, '', '', '', '');
rex_metainfo_add_field('Autor', 'med_author', 4, '', 1, '', '', '', '');
rex_metainfo_add_field('Ausrichtung', 'med_orientation', 5, '', 1, '', '', '', '');
rex_metainfo_add_field('Erstellungsdatum', 'med_createdate', 6, '', 1, '', '', '', '');
rex_metainfo_add_field('GPS Breitengrad', 'med_gps_lat', 7, '', 1, '', '', '', '');
rex_metainfo_add_field('GPS Längengrad', 'med_gps_long', 8, '', 1, '', '', '', '');

