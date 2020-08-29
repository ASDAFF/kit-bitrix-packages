<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

/** @var array $arCurrentValues */

use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Web\Json;

use Bitrix\Main\Localization\Loc;
use Sotbit\Origami\Config;
use Sotbit\Origami\Helper;

if (!Loader::includeModule('iblock'))
	return;

Loader::includeModule('kit.origami');

$arTemplateParameters['DETAIL_SHOW_POPULAR'] = array(
	'PARENT' => 'ADDITIONAL_SETTINGS',
	'NAME' => GetMessage('DETAIL_SHOW_POPULAR'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y'
);


?>
