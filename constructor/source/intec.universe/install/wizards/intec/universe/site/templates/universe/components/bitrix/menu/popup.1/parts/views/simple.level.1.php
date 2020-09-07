<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\Html;

/**
 * @var Closure $arParams
 * @var Closure $arResult
 * @var Closure $fRender
 */

/**
 * @param array $arItems
 * @param integer $iLevel
 * @param array $arParent
 */
return function ($arItems, $iLevel, $arParent = null) use (&$arParams, &$arResult, &$fRender) { ?>
    <div class="menu-item-items" data-role="menu.items" data-view="simple.level.1">
        <?php foreach ($arItems as $arItem) { ?>
        <?php
            $sName = $arItem['TEXT'];
            $sLink = $arItem['LINK'];
            $arChildren = $arItem['ITEMS'];

            $bActive = $arItem['ACTIVE'];
            $bSelected = $arItem['SELECTED'];
            $sTag = $bActive ? 'span' : 'a';
        ?>
            <?= Html::beginTag('div', [
                'class' => [
                    'menu-item' => [
                        '',
                        'level-'.$iLevel
                    ]
                ],
                'data' => [
                    'active' => $bActive ? 'true' : 'false',
                    'selected' => $bSelected ? 'true' : 'false',
                    'expanded' => $bSelected,
                    'role' => 'menu.item',
                    'level' => $iLevel
                ]
            ]) ?>
                <div class="menu-item-content intec-grid intec-grid-nowrap intec-grid-a-v-center intec-grid-i-h-4">
                    <div class="intec-grid-item-auto intec-grid-item-shrink-1">
                        <?= Html::tag($sTag, $sName, [
                            'class' => Html::cssClassFromArray([
                                'menu-item-name' => true,
                                'intec-cl-text-hover' => true,
                                'intec-cl-text' => $bSelected
                            ], true),
                            'href' => $sTag == 'a' ? $sLink : null
                        ]) ?>
                    </div>
                    <?php if (!empty($arChildren)) { ?>
                        <div class="intec-grid-item-auto" data-action="menu.item.toggle">
                            <div class="menu-item-icon intec-cl-background-hover">
                                <i class="fal fa-angle-down"></i>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <?php if (!empty($arChildren)) { ?>
                    <?php $fRender($arChildren, $iLevel + 1, $arItem) ?>
                <?php } ?>
            <?= Html::endTag('div') ?>
        <?php } ?>
    </div>
<?php };