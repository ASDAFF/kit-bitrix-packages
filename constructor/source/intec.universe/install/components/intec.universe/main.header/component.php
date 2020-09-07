<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arParams
 * @var CBitrixComponent $this
 */

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/'
];

$arLogotype = [
    'SHOW' => ArrayHelper::getValue($arParams, 'LOGOTYPE_SHOW') == 'Y',
    'PATH' => ArrayHelper::getValue($arParams, 'LOGOTYPE', null)
];

$arLogotype['PATH'] = trim($arLogotype['PATH']);
$arLogotype['PATH'] = StringHelper::replaceMacros($arLogotype['PATH'], $arMacros);
$arLogotype['SHOW'] = $arLogotype['SHOW'] && !empty($arLogotype['PATH']);

$arPhones['SHOW'] = ArrayHelper::getValue($arParams, 'PHONES_SHOW') == 'Y';
$arPhones['VALUES'] = ArrayHelper::getValue($arParams, 'PHONES');
$arPhones['VALUES'] = Type::isArray($arPhones['VALUES']) ? $arPhones['VALUES'] : [];

foreach ($arPhones['VALUES'] as $sKey => $sPhone) {
    if (empty($sPhone)) {
        unset($arPhones['VALUES'][$sKey]);
        continue;
    }

    $arPhones['VALUES'][$sKey] = [
        'DISPLAY' => $sPhone,
        'VALUE' => StringHelper::replace($sPhone, [
            '(' => '',
            ')' => '',
            ' ' => '',
            '-' => ''
        ])
    ];
}

$arPhones['SHOW'] = $arPhones['SHOW'] && !empty($arPhones['VALUES']);

$arResult['LOGOTYPE'] = $arLogotype;
$arResult['PHONES'] = $arPhones;

$this->includeComponentTemplate();