<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this, true));

?>
<!--noindex-->
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'sale-basket-icons-items'
    ]
]) ?>
    <?php if ($arResult['BASKET']['SHOW']) { ?>
        <div class="menu-information-item">
            <?= Html::beginTag('a', [
                'class' => [
                    'menu-information-link'
                ],
                'href' => $arResult['BASKET']['URL']
            ]) ?>
                <i class="menu-information-icon glyph-icon-cart"></i>
                <span>
                    <?= Loc::getMessage('C_MENU_MOBILE_2_SALE_BASKET_ICONS_BASKET') ?>
                </span>
            <?= Html::endTag('a') ?>
        </div>
    <?php } ?>
    <?php if ($arResult['DELAY']['SHOW']) { ?>
        <div class="menu-information-item">
            <?= Html::beginTag('a', [
                'class' => [
                    'menu-information-link'
                ],
                'href' => $arResult['DELAY']['URL']
            ]) ?>
                <i class="menu-information-icon glyph-icon-heart"></i>
                <span>
                    <?= Loc::getMessage('C_MENU_MOBILE_2_SALE_BASKET_ICONS_DELAY') ?>
                </span>
            <?= Html::endTag('a') ?>
        </div>
    <?php } ?>
    <?php if ($arResult['COMPARE']['SHOW']) { ?>
        <div class="menu-information-item">
            <?= Html::beginTag('a', [
                'class' => [
                    'menu-information-link'
                ],
                'href' => $arResult['COMPARE']['URL']
            ]) ?>
                <i class="menu-information-icon glyph-icon-compare"></i>
                <span>
                    <?= Loc::getMessage('C_MENU_MOBILE_2_SALE_BASKET_ICONS_COMPARE') ?>
                </span>
            <?= Html::endTag('a') ?>
        </div>
    <?php } ?>
<?= Html::endTag('div') ?>
<!--/noindex-->