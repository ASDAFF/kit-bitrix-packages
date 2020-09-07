<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var array $arResult
 * @var array $arParams
 * @var CBitrixComponent $this
 */

if (!Loader::includeModule('intec.core'))
    return;

$arParams = ArrayHelper::merge([
    'LOGOTYPE_SHOW' => 'N',
    'LOGOTYPE_PATH' => null,
    'PHONE_SHOW' => 'N',
    'PHONE_VALUE' => null
], $arParams);

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/'
];

$arResult['LOGOTYPE'] = [
    'SHOW' => $arParams['LOGOTYPE_SHOW'] === 'Y',
    'PATH' => $arParams['LOGOTYPE_PATH']
];

if (!empty($arResult['LOGOTYPE']['PATH'])) {
    $arResult['LOGOTYPE']['PATH'] = StringHelper::replaceMacros($arResult['LOGOTYPE']['PATH'], $arMacros);
} else {
    $arResult['LOGOTYPE']['SHOW'] = false;
}

$arResult['PHONE'] = [
    'SHOW' => $arParams['PHONE_SHOW'] === 'Y',
    'VALUE' => [
        'DISPLAY' => $arParams['PHONE_VALUE'],
        'LINK' => StringHelper::replace($arParams['PHONE_VALUE'], [
            '(' => '',
            ')' => '',
            ' ' => '',
            '-' => ''
        ])
    ]
];

if (empty($arResult['PHONE']['VALUE']['DISPLAY']))
    $arResult['PHONE']['SHOW'] = false;

$this->includeComponentTemplate();