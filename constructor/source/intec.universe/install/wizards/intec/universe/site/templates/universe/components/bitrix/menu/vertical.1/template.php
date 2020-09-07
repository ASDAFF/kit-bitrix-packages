<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Type;

/**
 * @var array $arResult
 * @var array $arParams
 */

if (!Loader::includeModule('intec.core'))
    return;

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

if (defined('EDITOR'))
    $arResult = [[
        'SELECTED' => false,
        'ACTIVE' => false,
        'TEXT' => Loc::getMessage('C_MENU_VERTICAL_1_STUB_ITEM_1'),
        'LINK' => null,
        'ITEMS' => []
    ], [
        'SELECTED' => false,
        'ACTIVE' => false,
        'TEXT' => Loc::getMessage('C_MENU_VERTICAL_1_STUB_ITEM_2'),
        'LINK' => null,
        'ITEMS' => []
    ], [
        'SELECTED' => false,
        'ACTIVE' => false,
        'TEXT' => Loc::getMessage('C_MENU_VERTICAL_1_STUB_ITEM_3'),
        'LINK' => null,
        'ITEMS' => []
    ], [
        'SELECTED' => false,
        'ACTIVE' => false,
        'TEXT' => Loc::getMessage('C_MENU_VERTICAL_1_STUB_ITEM_4'),
        'LINK' => null,
        'ITEMS' => []
    ]];

if (empty($arResult))
    return;

$sView = ArrayHelper::getValue($arParams, 'VIEW');
$fView = null;

include(__DIR__.'/parts/views.php');

$sView = ArrayHelper::fromRange(ArrayHelper::getKeys($arViews), $sView);
$fView = $arViews[$sView];
$iLevel = 0;

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => Html::cssClassFromArray([
        'ns-bitrix' => true,
        'c-menu' => true,
        'c-menu-vertical-1' => true
    ], true),
    'data' => [
        'role' => 'menu'
    ]
]) ?>
    <div class="menu-wrapper">
        <?php foreach ($arResult as $arItem) { ?>
        <?php
            $bSelected = ArrayHelper::getValue($arItem, 'SELECTED');
            $bSelected = Type::toBoolean($bSelected);
            $bActive = ArrayHelper::getValue($arItem, 'ACTIVE');
            $sTag = $bActive ? 'div' : 'a';
        ?>
            <?= Html::beginTag('div', [
                'class' => Html::cssClassFromArray([
                    'menu-item' => true,
                    'intec-cl' => [
                        'text-hover' => true,
                        'background' => $bSelected,
                        'border' => $bSelected
                    ]
                ], true),
                'data' => [
                    'active' => $bActive ? 'true' : 'false',
                    'selected' => $bSelected ? 'true' : 'false',
                    'role' => 'item',
                    'level' => $iLevel
                ]
            ]) ?>
                <?= Html::beginTag($sTag, [
                    'class' => 'menu-item-text',
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
    </div>
    <script type="text/javascript">
        (function ($, api) {
            var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
            var items = root.find('[data-role=item]');

            items.each(function () {
                var item = $(this);
                var menu = item.find('[data-role=menu]').first();

                if (menu.length > 0) {
                    item.on('mouseover', function () {
                        menu.show().stop().animate({
                            'opacity': 1
                        }, 300);
                    }).on('mouseout', function () {
                        menu.stop().animate({
                            'opacity': 0
                        }, 300, function () {
                            menu.hide();
                        });
                    });
                }
            });
        })(jQuery, intec);
    </script>
<?= Html::endTag('div') ?>