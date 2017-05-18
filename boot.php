<?php

rex_extension::register('MEDIA_ADDED', ['rex_mediapool_exif', 'processUploadedMedia'], rex_extension::LATE );
