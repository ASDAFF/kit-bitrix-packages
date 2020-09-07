<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $sTemplateId
 */

use intec\core\helpers\Html;
use intec\core\helpers\Type;

return function ($arProperty, &$arItem) use (&$arResult) {

if ($arResult['DIFFERENT'] && !$arProperty['DIFFERENT'])
    return;

if ($arProperty['HIDDEN'])
    return;

$sCode = $arProperty['CODE'];

if (empty($sCode))
    if (isset($arProperty['ID'])) {
        $sCode = $arProperty['ID'];
    } else {
        return;
    }

$sValue = null;

if ($arProperty['ENTITY'] === 'product') {
    if ($arProperty['TYPE'] === 'field') {
        $sValue = $arItem['FIELDS'][$sCode];
    } else if ($arProperty['TYPE'] === 'property') {
        $sValue = $arItem['DISPLAY_PROPERTIES'][$sCode]['DISPLAY_VALUE'];
    }
} else if ($arProperty['ENTITY'] === 'offer') {
    if ($arProperty['TYPE'] === 'field') {
        $sValue = $arItem['OFFER_FIELDS'][$sCode];
    } else if ($arProperty['TYPE'] === 'property') {
        $sValue = $arItem['OFFER_DISPLAY_PROPERTIES'][$sCode]['DISPLAY_VALUE'];
    }
}

if (Type::isArray($sValue))
    $sValue = implode(', ', $sValue);

?>
    <?= Html::beginTag('div', [
        'class' => 'catalog-compare-property',
        'data' => [
            'code' => $sCode,
            'role' => 'property',
            'entity' => $arProperty['ENTITY'],
            'type' => $arProperty['TYPE']
        ]
    ]) ?>
        <div class="catalog-compare-property-content">
            <div class="catalog-compare-property-name">
                <?= $arProperty['NAME'] ?>
            </div>
            <div class="catalog-compare-property-value">
                <?= $sValue ?>
            </div>
        </div>
        <?= Html::beginTag('div', [
            'class' => 'catalog-compare-property-remove',
            'data' => [
                'action' => $arProperty['ACTION'],
                'role' => 'property.remove'
            ]
        ]) ?>
            <i class="fal fa-times"></i>
        <?= Html::endTag('div') ?>
    <?= Html::endTag('div') ?>
<?php };