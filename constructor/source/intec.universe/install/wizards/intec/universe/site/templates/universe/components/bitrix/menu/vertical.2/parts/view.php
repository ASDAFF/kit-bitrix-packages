<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var Closure $fView
 */

/**
 * @param array $arItems
 * @param integer $iLevel
 */
return function ($arItems, $iLevel) use (&$fView) { ?>
    <?php foreach ($arItems as $arItem) { ?>
    <?php
        $bSelected = ArrayHelper::getValue($arItem, 'SELECTED');
        $bSelected = Type::toBoolean($bSelected);
        $bActive = ArrayHelper::getValue($arItem, 'ACTIVE');
        $sTag = $bActive ? 'div' : 'a';
        $iCount = ArrayHelper::getValue($arItem, ['PARAMS', 'SECTION', 'ELEMENT_CNT']);

        if ($iCount !== null)
            $iCount = Type::toInteger($iCount);
    ?>
        <?= Html::beginTag('div', [
            'class' => 'menu-item',
            'data' => [
                'active' => $bActive ? 'true' : 'false',
                'selected' => $bSelected ? 'true' : 'false',
                'role' => 'item',
                'level' => $iLevel
            ]
        ]) ?>
        <?= Html::beginTag($sTag, [
            'class' => 'menu-item-text intec-cl-text-hover',
            'href' => !$bActive ? $arItem['LINK'] : null
        ]) ?>
            <div class="menu-item-text-wrapper intec-grid intec-grid-nowrap intec-grid-a-v-center intec-grid-i-h-3">
                <div class="menu-item-text-name intec-grid-item-auto">
                    <?= $arItem['TEXT'] ?>
                </div>
                <?php if ($iCount !== null) { ?>
                    <div class="menu-item-text-counter intec-grid-item-auto">
                        <?= $iCount ?>
                    </div>
                <?php } ?>
            </div>
        <?= Html::endTag($sTag) ?>
        <?php if (!empty($arItem['ITEMS'])) { ?>
            <?= Html::beginTag('div', [
                'class' => 'menu-item-items',
                'data' => [
                    'expanded' => $bSelected ? 'true' : 'false',
                    'role' => 'menu'
                ]
            ]) ?>
                <?php $fView($arItem['ITEMS'], $iLevel + 1) ?>
            <?= Html::endTag('div') ?>
        <?php } ?>
        <?= Html::endTag('div') ?>
    <?php } ?>
<?php };