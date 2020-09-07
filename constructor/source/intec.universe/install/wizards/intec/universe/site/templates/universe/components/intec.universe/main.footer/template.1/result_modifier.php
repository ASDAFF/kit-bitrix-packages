<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\component\InnerTemplates;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 * @global $APPLICATION
 */

if (!Loader::includeModule('intec.core') || !Loader::includeModule('iblock'))
    return;

Loc::loadMessages(__FILE__);

$arParams = ArrayHelper::merge([
    'CATALOG_URL' => null,
    'CONSENT_URL' => null,
    'SEARCH_URL' => null,
    'SETTINGS_USE' => 'N',
    'PRODUCTS_VIEWED_SHOW' => 'N',
    'TEMPLATE' => null,
    'THEME' => 'light'
], $arParams);

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/'
];

include(__DIR__.'/modifiers/regionality.php');
include(__DIR__.'/modifiers/contacts.php');
include(__DIR__.'/modifiers/copyright.php');
include(__DIR__.'/modifiers/forms.php');
include(__DIR__.'/modifiers/menu.php');
include(__DIR__.'/modifiers/search.php');
include(__DIR__.'/modifiers/social.php');

$arResult['URL'] = [
    'CATALOG' => $arParams['CATALOG_URL'],
    'CONSENT' => $arParams['CONSENT_URL'],
    'SEARCH' => $arParams['SEARCH_URL']
];

foreach ($arResult['URL'] as $sKey => $sUrl)
    $arResult['URL'][$sKey] = StringHelper::replaceMacros(
        $sUrl,
        $arMacros
    );

$arResult['THEME'] = ArrayHelper::fromRange([
    'light',
    'dark'
], $arParams['THEME']);

$arResult['TEMPLATE'] = InnerTemplates::findOne($this, 'templates', $arParams['TEMPLATE']);

if (!empty($arResult['TEMPLATE']))
    $arResult['TEMPLATE']->modify($arParams, $arResult);