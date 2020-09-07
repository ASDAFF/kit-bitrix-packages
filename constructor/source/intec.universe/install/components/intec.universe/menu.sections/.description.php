<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;

$arComponentDescription = [
	'NAME' => Loc::getMessage('MENU_SECTIONS_NAME'),
	'DESCRIPTION' => Loc::getMessage('MENU_SECTIONS_DESCRIPTION'),
	'ICON' => '/images/menu_ext.gif',
	'CACHE_PATH' => 'Y',
	'PATH' => [
		'ID' => 'Universe'
	]
];