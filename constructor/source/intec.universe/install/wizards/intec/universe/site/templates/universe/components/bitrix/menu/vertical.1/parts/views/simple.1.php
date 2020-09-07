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
return function ($arItems, $iLevel) use (&$sView, &$fView) { ?>
    <?php foreach ($arItems as $arItem) { ?>
    <?php
        $bSelected = ArrayHelper::getValue($arItem, 'SELECTED');
        $bSelected = Type::toBoolean($bSelected);
        $bActive = ArrayHelper::getValue($arItem, 'ACTIVE');
        $sTag = $bActive ? 'div' : 'a';
    ?>
        <?= Html::beginTag('div', [
            'class' => 'menu-item-submenu-item intec-cl-text-hover',
            'data' => [
                'active' => $bActive ? 'true' : 'false',
                'selected' => $bSelected ? 'true' : 'false',
                'role' => 'item',
                'level' => $iLevel
            ]
        ]) ?>
            <?= Html::beginTag($sTag, [
                'class' => 'menu-item-submenu-item-text',
                'href' => !$bActive ? $arItem['LINK'] : null
            ]) ?>
                <?= $arItem['TEXT'] ?>
                <?php if (!empty($arItem['ITEMS'])) { ?>
                    <div class="menu-item-arrow">
                        <i class="fal fa-angle-right"></i>
                    </div>
                <?php } ?>
            <?= Html::endTag($sTag) ?>
            <?php if (!empty($arItem['ITEMS'])) { ?>
                <?= Html::beginTag('div', [
                    'class' => 'menu-item-submenu',
                    'data' => [
                        'expanded' => 'false',
                        'role' => 'menu',
                        'view' => $sView
                    ]
                ]) ?>
                    <?php $fView($arItem['ITEMS'], $iLevel + 1) ?>
                <?= Html::endTag('div') ?>
            <?php } ?>
        <?= Html::endTag('div') ?>
    <?php } ?>
<?php };