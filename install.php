<?php

rex_sql_table::get(rex::getTable('media'))
    ->ensurePrimaryIdColumn()
    ->ensureColumn(new rex_sql_column('exif_json', 'longtext', true))
    ->ensure();

