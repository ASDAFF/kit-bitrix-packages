<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\net\Url;

if (!Loader::includeModule('intec.core'))
    return;

if (!Loader::includeModule('iblock'))
    return;

/**
 * @global $APPLICATION
 * @global $USER
 * @var array $arParams
 * @var array $arResult
 */

$arMacros = [
    'SITE_DIR' => SITE_DIR
];

$arResult['URL'] = [
    'BASKET' => ArrayHelper::getValue($arParams, 'BASKET_URL')
];

foreach ($arResult['URL'] as $sKey => $sUrl)
    $arResult['URL'][$sKey] = StringHelper::replaceMacros($sUrl, $arMacros);
