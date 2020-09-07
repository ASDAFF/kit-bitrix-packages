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
    <div class="menu-item-items" data-role="menu.items" data-view="simple.level.2">
        <?php foreach ($arItems as $arItem) { ?>
        <?php
            $sName = $arItem['TEXT'];
            $sLink = $arItem['LINK'];

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
            <?= Html::endTag('div') ?>
        <?php } ?>
    </div>
<?php };