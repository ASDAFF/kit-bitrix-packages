<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\constructor\models\Build;

$arResult['CONSENT'] = [
    'SHOW' => false,
    'URL' => null
];

if (!Loader::includeModule('intec.core'))
    return;

if (!Loader::includeModule('intec.constructor') && !Loader::includeModule('intec.constructorlite'))
    return;

$arConsent = [
    'SHOW' => false,
    'URL' => ArrayHelper::getValue($arParams, 'CONSENT_URL')
];

$oBuild = Build::getCurrent();

if (!empty($oBuild)) {
    $oPage = $oBuild->getPage();
    $oProperties = $oPage->getProperties();
    $arConsent['SHOW'] = $oProperties->get('base-consent');
}

if (!empty($arConsent['URL'])) {
    $arConsent['URL'] = StringHelper::replaceMacros($arConsent['URL'], [
        'SITE_DIR' => SITE_DIR
    ]);
} else {
    $arConsent['SHOW'] = false;
}

$arResult['CONSENT'] = $arConsent;