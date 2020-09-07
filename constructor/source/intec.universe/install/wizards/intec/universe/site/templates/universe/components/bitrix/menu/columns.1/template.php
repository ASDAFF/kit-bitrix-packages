<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 */

if (!Loader::includeModule('intec.core'))
    return;

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

if (empty($arResult))
    return;

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-menu',
        'c-menu-columns-1'
    ]
]) ?>
    <?= Html::beginTag('div', [
        'class' => [
            'menu-columns',
            'intec-grid' => [
                '',
                'wrap',
                'a-h-start',
                'a-v-start',
                'i-h-10',
                'i-v-20',
            ]
        ]
    ]) ?>
        <?php foreach ($arResult as $arItem) { ?>
        <?php
            $bActive = $arItem['ACTIVE'];
            $bSelected = $arItem['SELECTED'];

            $sUrl = $bActive ? null : $arItem['LINK'];
            $sTag = $bActive ? 'span' : 'a';
        ?>
            <?= Html::beginTag('div', [
                'class' => [
                    'menu-column',
                    'intec-grid-item',
                    'intec-grid-item-550-1'
                ],
                'data' => [
                    'active' => $bActive ? 'true' : 'false',
                    'selected' => $bSelected ? 'true' : 'false'
                ]
            ]) ?>
                <div class="menu-column-header intec-cl-text">
                    <?= Html::tag($sTag, $arItem['TEXT'], [
                        'class' => 'menu-column-header-link',
                        'href' => $sUrl
                    ]) ?>
                </div>
                <?php if (!empty($arItem['ITEMS'])) { ?>
                    <div class="menu-column-items">
                        <?php foreach ($arItem['ITEMS'] as $arChild) { ?>
                        <?php
                            $bChildActive = $arChild['ACTIVE'];
                            $bChildSelected = $arChild['SELECTED'];

                            $sChildUrl = $bChildActive ? null : $arChild['LINK'];
                            $sChildTag = $bChildActive ? 'span' : 'a';
                        ?>
                            <?= Html::beginTag('div', [
                                'class' => 'menu-column-item',
                                'data' => [
                                    'active' => $bChildActive ? 'true' : 'false',
                                    'selected' => $bChildSelected ? 'true' : 'false'
                                ]
                            ]) ?>
                                <?= Html::tag($sChildTag, $arChild['TEXT'], [
                                    'class' => Html::cssClassFromArray([
                                        'menu-column-item-link' => true,
                                        'intec-cl-text' => $bChildSelected,
                                        'intec-cl-text-hover' => true
                                    ], true),
                                    'href' => $sChildUrl
                                ]) ?>
                            <?= Html::endTag('div') ?>
                        <?php } ?>
                    </div>
                <?php } ?>
             <?= Html::endTag('div') ?>
        <?php } ?>
    <?= Html::endTag('div') ?>
<?= Html::endTag('div') ?>