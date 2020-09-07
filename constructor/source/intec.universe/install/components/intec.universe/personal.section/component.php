<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die(); ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

if (!Loader::includeModule('intec.core'))
    return;

$arUrls = [
    'PERSONAL_DATA',
    'BASKET',
    'ORDER',
    'CONTACTS'
];

$arResult['URL'] = [];

foreach ($arUrls as $sUrl) {
    $sValue = ArrayHelper::getValue($arParams, 'URL_'.$sUrl);
    $sValue = StringHelper::replaceMacros($sValue, [
        'SITE_DIR' => SITE_DIR
    ]);

    $arResult['URL'][$sUrl] = $sValue;
}

$this->IncludeComponentTemplate();