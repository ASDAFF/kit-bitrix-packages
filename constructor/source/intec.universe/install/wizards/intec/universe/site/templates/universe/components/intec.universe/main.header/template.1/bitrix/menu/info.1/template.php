<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var CBitrixComponentTemplate $this
 * @var CBitrixComponent $component
 * @var array $arResult
 */

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

?>
<?php if (!empty($arResult)) { ?>
    <div id="<?= $sTemplateId ?>" class="widget-menu widget-menu-info-1">
        <?= Html::beginTag('div', [
            'class' => [
                'widget-menu-items',
                'intec-grid' => [
                    '',
                    'nowrap',
                    'a-v-center',
                    'i-h-13'
                ]
            ]
        ]) ?>
            <?php foreach ($arResult as $arItem) { ?>
            <?php
                $bSelected = ArrayHelper::getValue($arItem, 'SELECTED');
                $bSelected = Type::toBoolean($bSelected);
                $sTag = $bSelected ? 'span' : 'a';
                $sUrl = $bSelected ? null : $arItem['LINK'];
            ?>
                <div class="widget-menu-item<?= $bSelected ? ' widget-menu-item-active intec-cl-text' : null ?> intec-cl-text-hover intec-grid-item-auto">
                    <?= Html::tag($sTag, $arItem['TEXT'], [
                        'class' => 'widget-menu-item-text',
                        'href' => $sUrl
                    ])?>
                </div>
            <?php } ?>
        <?= Html::endTag('div') ?>
    </div>
<?php } ?>