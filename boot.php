<?php

rex_fragment::addDirectory(realpath(__DIR__));


rex_extension::register('MEDIA_ADDED', ['rex_mediapool_exif', 'processUploadedMedia'], rex_extension::LATE );
rex_extension::register('MEDIA_UPDATED', ['rex_mediapool_exif', 'processUploadedMedia'], rex_extension::LATE );
rex_extension::register('MEDIA_DETAIL_SIDEBAR', ['rex_mediapool_exif', 'mediapoolDetailOutput']);
