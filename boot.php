<?php

if (class_exists(rex_fragment::class)) {
	rex_fragment::addDirectory(realpath(__DIR__));
}

rex_extension::register('MEDIA_ADDED', ['rex_mediapool_exif', 'processUploadedMedia'], rex_extension::LATE );
rex_extension::register('MEDIA_DETAIL_SIDEBAR', ['rex_mediapool_exif', 'mediapoolDetailOutput']);
