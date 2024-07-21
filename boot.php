<?php

use FriendsOfRedaxo\MediapoolExif;

/** @phpstan-ignore-next-line */
spl_autoload_register(['FriendsOfRedaxo\\MediapoolExif\\Autoload', 'autoload'], true, true); // remove in v4

class_alias(MediapoolExif\Exception\InvalidClassException::class, 'FriendsOfRedaxo\\addon\\MediapoolExif\\Exception\\InvalidClassException');// remove in v4
class_alias(MediapoolExif\Exception\InvalidFormatExcption::class, 'FriendsOfRedaxo\\addon\\MediapoolExif\\Exception\\InvalidFormatExcption');// remove in v4
class_alias(MediapoolExif\Exception\IptcException::class, 'FriendsOfRedaxo\\addon\\MediapoolExif\\Exception\\IptcException');// remove in v4
class_alias(MediapoolExif\Exception\NotFoundException::class, 'FriendsOfRedaxo\\addon\\MediapoolExif\\Exception\\NotFoundException');// remove in v4

$dir = realpath(__DIR__);
if ($dir !== false) {
	rex_fragment::addDirectory($dir);
}


$class = MediapoolExif\MediapoolExif::class;
rex_extension::register('MEDIA_ADDED', [$class, 'processUploadedMedia'], rex_extension::LATE);
rex_extension::register('MEDIA_UPDATED', [$class, 'processUploadedMedia'], rex_extension::LATE);
rex_extension::register('MEDIA_DETAIL_SIDEBAR', [$class, 'mediapoolDetailOutput']);
