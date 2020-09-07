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

return function ($arProperty, &$arItem) use (&$arResult) {

if ($arResult['DIFFERENT'] && !$arProperty['DIFFERENT'])
    return;

if (!$arProperty['HIDDEN'])
    return;

$sCode = $arProperty['CODE'];

if (empty($sCode))
    if (isset($arProperty['ID'])) {
        $sCode = $arProperty['ID'];
    } else {
        return;
    }

?>
    <div class="intec-grid-item-auto">
        <?= Html::beginTag('div', [
            'class' => [
                'catalog-compare-label',
                'intec-ui' => [
                    '',
                    'control-button',
                    'mod-transparent',
                    'mod-round-2',
                    'scheme-current'
                ]
            ],
            'data' => [
                'action' => $arProperty['ACTION'],
                'code' => $sCode,
                'role' => 'label',
                'entity' => $arProperty['ENTITY'],
                'type' => $arProperty['TYPE']
            ]
        ]) ?>
            <div class="catalog-compare-label-wrapper">
                + <?= $arProperty['NAME'] ?>
            </div>
        <?= Html::endTag('div') ?>
    </div>
<?php };