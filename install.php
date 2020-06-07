<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

rex_sql_table::get(rex::getTable('media'))
    ->ensurePrimaryIdColumn()
    ->ensureColumn(new rex_sql_column('exif_json', 'longtext', true))
    ->ensure();

