<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\helpers\ArrayHelper;
use intec\core\helpers\FileHelper;
use intec\core\helpers\Html;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var int $iLevel
 * @var array $arItem
 * @var array $arItems
 * @var boolean $bIsIBlock
 */

$bFirstItem = true;

?>
<div class="menu-submenu menu-submenu-<?= $iLevel ?>" data-role="menu">
    <div class="menu-submenu-items" data-role="items">
        <?php foreach ($arItems as $arItem) { ?>
        <?php
            $bActive = $arItem['ACTIVE'];
            $bSelected = ArrayHelper::getValue($arItem, 'SELECTED');
            $bSelected = Type::toBoolean($bSelected);

            $bSubmenu = !empty($arItem['ITEMS']) && !$bIsIBlock;

            $sUrl = $bActive ? null : $arItem['LINK'];
            $sTag = $bActive ? 'div' : 'a';
        ?>
            <div class="menu-submenu-item<?= $bSelected ? ' menu-submenu-item-active' : null ?>" data-role="item">
                <?= Html::beginTag($sTag, array(
                    'class' => 'menu-submenu-item-text intec-cl-text-hover'.($bSelected ? ' intec-cl-text' : null),
                    'href' => $sUrl
                )); ?>
                    <?= Html::encode($arItem['TEXT']) ?>
                <?= Html::endTag($sTag) ?>
                <?php if ($bSubmenu) { ?>
                    <div class="menu-submenu-item-arrow far fa-angle-right"></div>
                <?php } ?>
                <?php if ($bSubmenu) $fDraw($arItem, $iLevel + 1) ?>
            </div>
            <?php $bFirstItem = false ?>
        <?php } ?>
    </div>
    <?php if ($bIsIBlock && $arParams['SECTION_VIEW'] === 'information') { ?>
        <div class="menu-submenu-cards" data-role="cards">
            <?php foreach ($arItems as $arItem) { ?>
            <?php
                $bActive = $arItem['ACTIVE'];
                $bSelected = ArrayHelper::getValue($arItem, 'SELECTED');
                $bSelected = Type::toBoolean($bSelected);

                $arImage = [
                    'TYPE' => 'picture',
                    'SOURCE' => null
                ];

                if (!empty($arItem['IMAGE'])) {
                    if ($arItem['IMAGE']['CONTENT_TYPE'] === 'image/svg+xml') {
                        $arImage['TYPE'] = 'svg';
                        $arImage['SOURCE'] = $arItem['IMAGE']['SRC'];
                    } else {
                        $arImage['SOURCE'] = CFile::ResizeImageGet($arItem['IMAGE'], array(
                            'width' => 130,
                            'height' => 130
                        ), BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

                        if (!empty($arImage['SOURCE'])) {
                            $arImage['SOURCE'] = $arImage['SOURCE']['src'];
                        } else {
                            $arImage['SOURCE'] = null;
                        }
                    }
                }

                if (empty($arImage['SOURCE'])) {
                    $arImage['TYPE'] = 'picture';
                    $arImage['SOURCE'] = SITE_TEMPLATE_PATH.'/images/picture.missing.png';
                }

                $sUrl = $bActive ? null : $arItem['LINK'];
                $sTag = $bActive ? 'div' : 'a';
                $sDescription = null;

                if (!empty($arItem['PARAMS']['SECTION']) && !empty($arItem['PARAMS']['SECTION']['DESCRIPTION'])) {
                    $sDescription = Html::stripTags($arItem['PARAMS']['SECTION']['DESCRIPTION'], ['br']);
                } else if (!empty($arItem['PARAMS']['ELEMENT']) && !empty($arItem['PARAMS']['ELEMENT']['PREVIEW_TEXT'])) {
                    $sDescription = Html::stripTags($arItem['PARAMS']['ELEMENT']['PREVIEW_TEXT'], ['br']);
                }
            ?>
                <div class="menu-submenu-card" data-role="card" data-expanded="false">
                    <div class="menu-submenu-card-wrapper">
                        <div class="menu-submenu-card-wrapper-2">
                            <div class="menu-submenu-card-image">
                                <?php if ($arImage['TYPE'] === 'svg') { ?>
                                    <?= Html::tag('a', FileHelper::getFileData('@root/'.$arImage['SOURCE']), [
                                        'href' => $sUrl,
                                        'class' => 'intec-cl-svg'
                                    ]) ?>
                                <?php } else { ?>
                                    <?= Html::tag('a', null, [
                                        'href' => $sUrl,
                                        'data' => [
                                            'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                            'original' => $arVisual['LAZYLOAD']['USE'] ? $arImage['SOURCE'] : null
                                        ],
                                        'style' => [
                                            'background-image' => !$arVisual['LAZYLOAD']['USE'] ? 'url(\''.$arImage['SOURCE'].'\')' : null
                                        ]
                                    ]) ?>
                                <?php } ?>
                            </div>
                            <?= Html::tag($sTag, $arItem['TEXT'], [
                                'class' => 'menu-submenu-card-name',
                                'href' => $sUrl
                            ]) ?>
                            <?php if (!empty($sDescription)) { ?>
                                <div class="menu-submenu-card-description">
                                    <?= $sDescription ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
    <div class="intec-ui-clear"></div>
</div>