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
    <?= Html::beginTag('div', [
        'class' => [
            'menu-content-wrapper-4',
            'intec-grid' => [
                '',
                'wrap',
                'a-v-begin',
                'a-h-begin',
                'i-h-10'
            ]
        ],
        'data' => [
            'role' => 'menu.items',
            'view' => 'simple.level.0'
        ]
    ]) ?>
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
                    ],
                    'intec-grid-item' => [
                        '4',
                        '768-3',
                        '500-2'
                    ]
                ],
                'data' => [
                    'active' => $bActive ? 'true' : 'false',
                    'selected' => $bSelected ? 'true' : 'false',
                    'role' => 'menu.item',
                    'level' => $iLevel
                ]
            ]) ?>
                <?= Html::tag($sTag, $sName, [
                    'class' => Html::cssClassFromArray([
                        'menu-item-name' => true,
                        'intec-cl-text-hover' => true,
                        'intec-cl-text' => $bSelected
                    ], true),
                    'href' => $sTag == 'a' ? $sLink : null
                ]) ?>
                <?php if (!empty($arChildren)) { ?>
                    <?php $fRender($arChildren, $iLevel + 1, $arItem) ?>
                <?php } ?>
            <?= Html::endTag('div') ?>
        <?php } ?>
    <?= Html::endTag('div') ?>
<?php };