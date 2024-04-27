<?php

use FriendsOfRedaxo\addon\MediapoolExif\MediapoolExif;

$dir = realpath(__DIR__);
if ($dir !== false) {
	rex_fragment::addDirectory($dir);
}


$class = MediapoolExif::class;
rex_extension::register('MEDIA_ADDED', [$class, 'processUploadedMedia'], rex_extension::LATE);
rex_extension::register('MEDIA_UPDATED', [$class, 'processUploadedMedia'], rex_extension::LATE);
rex_extension::register('MEDIA_DETAIL_SIDEBAR', [$class, 'mediapoolDetailOutput']);
