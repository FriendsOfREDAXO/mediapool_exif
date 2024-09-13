<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPEnum.php to edit this template
 */
namespace FriendsOfRedaxo\MediapoolExif\Enum;

/**
 *
 * @author akrys
 */
enum IptcDefinitions
{
	case DOCUMENT_TITLE; //2#005'  | DocumentTitle
	case URGENCY; //2#010'  | Urgency
	case CATEGORY; //2#015'  | Category
	case SUBCATEGORIES; //2#020'  | Subcategories
	case KEYWORDS; //2#025'  | Keywords
	case SPECIAL_INSTRUCTIONS; //2#040'  | SpecialInstructions
	case CREATION_DATE; //2#055'  | CreationDate
	case AUTHOR_BY_LINE; //2#080'  | AuthorByline
	case AUTHOR_TITLE; //2#085'  | AuthorTitle
	case CITY; //2#090'  | City
	case STATE; //2#095'  | State
	case COUNTRY; //2#101'  | Country
	case OTR; //2#103'  | OTR
	case HEADLINE; //2#105'  | Headline
	case SOURCE; //2#110'  | Source
	case PHOTO_SOURCE; //2#115'  | PhotoSource
	case COPYRIGHT; //2#116'  | Copyright
	case CAPTION; //2#120'  | Caption
	case CAPTION_WRITER; //2#122'  | CaptionWriter

	public function getCode(): string
	{
		return match ($this) {
			self::DOCUMENT_TITLE => '2#005',
			self::URGENCY => '2#010',
			self::CATEGORY => '2#015',
			self::SUBCATEGORIES => '2#020',
			self::KEYWORDS => '2#025',
			self::SPECIAL_INSTRUCTIONS => '2#040',
			self::CREATION_DATE => '2#055',
			self::AUTHOR_BY_LINE => '2#080',
			self::AUTHOR_TITLE => '2#085',
			self::CITY => '2#090',
			self::STATE => '2#095',
			self::COUNTRY => '2#101',
			self::OTR => '2#103',
			self::HEADLINE => '2#105',
			self::SOURCE => '2#110',
			self::PHOTO_SOURCE => '2#115',
			self::COPYRIGHT => '2#116',
			self::CAPTION => '2#120',
			self::CAPTION_WRITER => '2#122',
		};
	}

	public function getLabel(): string
	{
		return match ($this) {
			self::DOCUMENT_TITLE => 'DocumentTitle',
			self::URGENCY => 'Urgency',
			self::CATEGORY => 'Category',
			self::SUBCATEGORIES => 'Subcategories',
			self::KEYWORDS => 'Keywords',
			self::SPECIAL_INSTRUCTIONS => 'SpecialInstructions',
			self::CREATION_DATE => 'CreationDate',
			self::AUTHOR_BY_LINE => 'AuthorByline',
			self::AUTHOR_TITLE => 'AuthorTitle',
			self::CITY => 'City',
			self::STATE => 'State',
			self::COUNTRY => 'Country',
			self::OTR => 'OTR',
			self::HEADLINE => 'Headline',
			self::SOURCE => 'Source',
			self::PHOTO_SOURCE => 'PhotoSource',
			self::COPYRIGHT => 'Copyright',
			self::CAPTION => 'Caption',
			self::CAPTION_WRITER => 'CaptionWriter',
		};
	}
}
