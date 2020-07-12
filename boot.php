<?php

use FriendsOfRedaxo\addon\MediapoolExif\MediapoolExif;

rex_fragment::addDirectory(realpath(__DIR__));

$class = MediapoolExif::class;
rex_extension::register('MEDIA_ADDED', [$class, 'processUploadedMedia'], rex_extension::LATE);
rex_extension::register('MEDIA_UPDATED', [$class, 'processUploadedMedia'], rex_extension::LATE);
rex_extension::register('MEDIA_DETAIL_SIDEBAR', [$class, 'mediapoolDetailOutput']);
