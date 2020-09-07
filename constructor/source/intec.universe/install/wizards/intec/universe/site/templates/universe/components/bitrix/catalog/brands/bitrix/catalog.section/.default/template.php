<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\Component;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @global CDatabase $DB
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $templateFile
 * @var string $templateFolder
 * @var string $componentPath
 * @var CBitrixComponent $component
 */

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arLazyLoad = $arResult['LAZYLOAD'];

?>
<div class="brands">
    <?php if (!empty($arResult['DESCRIPTION'])) { ?>
        <div class="brands-description<?= empty($arResult['ITEMS']) ? ' brands-description-only' : '' ?> intec-ui-markup-text">
            <?= $arResult['DESCRIPTION'] ?>
        </div>
    <?php } ?>
    <?php if (!empty($arResult['ITEMS'])) { ?>
        <?php if ($arParams['DISPLAY_TOP_PAGER'] && !empty($arResult['NAV_STRING'])) { ?>
            <div class="brands-navigation brands-navigation-top">
                <!-- pagination-container -->
                <?= $arResult['NAV_STRING'] ?>
                <!-- pagination-container -->
            </div>
        <?php } ?>
        <div class="brands-wrapper">
            <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
            <?php
                $sId = $sTemplateId.'_'.$arItem['ID'];
                $sAreaId = $this->GetEditAreaId($sId);
                $this->AddEditAction($sId, $arItem['EDIT_LINK']);
                $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);
                $sImage = null;

                if (!empty($arItem['PREVIEW_PICTURE'])) {
                    $sImage = $arItem['PREVIEW_PICTURE'];
                } else if (!empty($arItem['DETAIL_PICTURE'])) {
                    $sImage = $arItem['DETAIL_PICTURE'];
                }

                $sImage = CFile::ResizeImageGet($sImage, array(
                    'width' => 520,
                    'height' => 440
                ), BX_RESIZE_IMAGE_PROPORTIONAL_ALT);

                if (!empty($sImage)) {
                    $sImage = $sImage['src'];
                } else {
                    $sImage = null;
                }
            ?>
                <div class="brand" id="<?= $sAreaId ?>">
                    <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>" class="brand-wrapper">
                        <?= Html::tag('div', '', [
                            'href' => $arItem['SECTION_PAGE_URL'],
                            'class' => [
                                'brand-wrapper-2'
                            ],
                            'data' => [
                                'lazyload-use' => $arLazyLoad['USE'] ? 'true' : 'false',
                                'original' => $arLazyLoad['USE'] ? $sImage : null
                            ],
                            'style' => [
                                'background-image' => !$arLazyLoad['USE'] ? 'url(\''.$sImage.'\')' : null
                            ]
                        ]) ?>
                    </a>
                </div>
            <?php } ?>
            <div class="clearfix"></div>
        </div>
        <?php if ($arParams['DISPLAY_BOTTOM_PAGER'] && !empty($arResult['NAV_STRING'])) { ?>
            <div class="brands-navigation brands-navigation-bottom">
                <!-- pagination-container -->
                <?= $arResult['NAV_STRING'] ?>
                <!-- pagination-container -->
            </div>
        <?php } ?>
    <?php } ?>
</div>
<div class="clearfix"></div>