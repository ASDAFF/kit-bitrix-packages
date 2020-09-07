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
        'intec-grid' => [
            '',
            'nowrap',
            'i-5',
            'a-v-center',
        ]
    ]
]) ?>
    <?php if ($arResult['BASKET']['SHOW']) { ?>
        <div class="intec-grid-item">
            <?= Html::beginTag('a', [
                'class' => [
                    'menu-information-button-link'
                ],
                'href' => $arResult['BASKET']['URL']
            ]) ?>
                <i class="menu-information-button-icon glyph-icon-cart"></i>
            <?= Html::endTag('a') ?>
        </div>
    <?php } ?>
    <?php if ($arResult['DELAY']['SHOW']) { ?>
        <div class="intec-grid-item">
            <?= Html::beginTag('a', [
                'class' => [
                    'menu-information-button-link'
                ],
                'href' => $arResult['DELAY']['URL']
            ]) ?>
                <i class="menu-information-button-icon glyph-icon-heart"></i>
            <?= Html::endTag('a') ?>
        </div>
    <?php } ?>
    <?php if ($arResult['COMPARE']['SHOW']) { ?>
        <div class="intec-grid-item">
            <?= Html::beginTag('a', [
                'class' => [
                    'menu-information-button-link'
                ],
                'href' => $arResult['COMPARE']['URL']
            ]) ?>
                <i class="menu-information-button-icon glyph-icon-compare"></i>
            <?= Html::endTag('a') ?>
        </div>
    <?php } ?>
<?= Html::endTag('div') ?>
<!--/noindex-->