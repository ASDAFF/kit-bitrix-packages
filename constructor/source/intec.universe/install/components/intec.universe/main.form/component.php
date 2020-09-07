<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;

/**
 * @var array $arResult
 * @var array $arParams
 */

if ($this->startResultCache()) {
    $sConsent = ArrayHelper::getValue($arParams, 'CONSENT');
    $sConsent = trim($sConsent);
    $sConsent = StringHelper::replaceMacros($sConsent, ['SITE_DIR' => SITE_DIR]);

    $arResult = [
        'ID' => ArrayHelper::getValue($arParams, 'ID'),
        'TEMPLATE' => ArrayHelper::getValue($arParams, 'TEMPLATE'),
        'CONSENT' => $sConsent,
        'NAME' => ArrayHelper::getValue($arParams, 'NAME')
    ];

    $this->IncludeComponentTemplate();
}