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
    <div class="menu-item-submenu-wrapper intec-grid intec-grid-wrap intec-grid-a-h-start intec-grid-a-v-center intec-grid-i-h-7 intec-grid-i-v-15">
        <?php foreach ($arItems as $arItem) { ?>
        <?php
            $bSelected = ArrayHelper::getValue($arItem, 'SELECTED');
            $bSelected = Type::toBoolean($bSelected);
            $bActive = ArrayHelper::getValue($arItem, 'ACTIVE');
            $sTag = $bActive ? 'div' : 'a';

            $sPicture = $arItem['PICTURE'];

            if (!empty($sPicture)) {
                $sPicture = CFile::ResizeImageGet($sPicture, array(
                    'width' => 45,
                    'height' => 45
                ), BX_RESIZE_IMAGE_PROPORTIONAL);

                if (!empty($sPicture))
                    $sPicture = $sPicture['src'];
            }

            if (empty($sPicture))
                $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';
        ?>
            <?= Html::beginTag($sTag, [
                'class' => [
                    'menu-item-submenu-item',
                    'intec-cl-text-hover',
                    'intec-grid-item-3'
                ],
                'href' => !$bActive ? $arItem['LINK'] : null,
                'data' => [
                    'active' => $bActive ? 'true' : 'false',
                    'selected' => $bSelected ? 'true' : 'false',
                    'role' => 'item',
                    'level' => $iLevel
                ]
            ]) ?>
                <div class="menu-item-submenu-item-wrapper intec-grid intec-grid-nowrap intec-grid-a-v-center intec-grid-i-h-15">
                    <div class="menu-item-submenu-item-part intec-grid-item-auto">
                        <div class="menu-item-submenu-item-picture" style="background-image: url('<?= $sPicture ?>')"></div>
                    </div>
                    <div class="menu-item-submenu-item-part intec-grid-item">
                        <div class="menu-item-submenu-item-text">
                            <?= $arItem['TEXT'] ?>
                        </div>
                    </div>
                </div>
            <?= Html::endTag($sTag) ?>
        <?php } ?>
    </div>
<?php };