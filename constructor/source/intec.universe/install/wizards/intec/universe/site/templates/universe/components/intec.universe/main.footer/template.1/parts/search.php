<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

if (empty($arSearch) || !Type::isArray($arSearch))
    $arSearch = [];

if (empty($arSearch['TEMPLATE']))
    return;

if (!isset($arSearch['PREFIX']))
    $arSearch['PREFIX'] = 'SEARCH_';

if (empty($arSearch['PARAMETERS']) || !Type::isArray($arSearch['PARAMETERS']))
    $arSearch['PARAMETERS'] = [];

if (!empty($arSearch['PREFIX']))
    foreach ($arParams as $sKey => $sValue)
        if (StringHelper::startsWith($sKey, $arSearch['PREFIX'])) {
            $sKey = StringHelper::cut($sKey, StringHelper::length($arSearch['PREFIX']));

            if (!ArrayHelper::keyExists($sKey, $arSearch['PARAMETERS']))
                $arSearch['PARAMETERS'][$sKey] = $sValue;
        }

$arSearch['PARAMETERS'] = ArrayHelper::merge($arSearch['PARAMETERS'], [
    'PAGE' => $arResult['SEARCH']['MODE'] === 'site' ? $arResult['URL']['SEARCH'] : $arResult['URL']['CATALOG']
]);

?>
<!--noindex-->
<?php $APPLICATION->IncludeComponent(
    'bitrix:search.title',
    $arSearch['TEMPLATE'],
    $arSearch['PARAMETERS'],
    $this->getComponent()
) ?>
<!--/noindex-->