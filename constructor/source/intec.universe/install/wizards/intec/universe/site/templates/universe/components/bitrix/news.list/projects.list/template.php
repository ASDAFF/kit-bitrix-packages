<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
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

$sTabId = "news-list-$sTemplateId-section-";
?>
<div class="ns-bitrix c-news-list c-news-list-projects-list">
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <ul class="nav nav-tabs intec-tabs news-list-tabs intec-ui-mod-simple">
                <?php $bSectionFirst = true ?>
                <?php foreach ($arResult['SECTIONS'] as $arSection) { ?>
                    <?php if (count($arSection['ITEMS']) <= 0) continue; ?>
                    <li role="presentation"<?= $bSectionFirst ? ' class="active"' : null ?>">
                        <a href="#<?=$sTabId.$arSection['ID'] ?>"
                           aria-controls="<?=$sTabId.$arSection['ID'] ?>"
                           role="tab"
                           data-toggle="tab"
                        ><?= $arSection['NAME'] ?></a>
                    </li>
                    <?php $bSectionFirst = false ?>
                <?php } ?>
            </ul>
            <div class="tab-content clearfix news-list-tab-container">
                <?php $bSectionFirst = true ?>
                <?php foreach ($arResult['SECTIONS'] as $arSection) { ?>
                    <?php if (count($arSection['ITEMS']) <= 0) continue; ?>
                    <div role="tabpanel"
                         id="<?=$sTabId.$arSection['ID'] ?>"
                         class="tab-pane<?= $bSectionFirst ? ' active' : null ?> project-tab"
                    >
                        <div class="news-list-elements">
                            <div class="news-list-elements-wrapper">
                                <?php foreach($arSection['ITEMS'] as $arItem) { ?>
                                <?php
                                    $sId = $sTemplateId.'_section_'.$arSection['ID'].'_desktop_default_'.$arItem['ID'];
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
                                        'width' => 340,
                                        'height' => 220
                                    ), BX_RESIZE_IMAGE_PROPORTIONAL_ALT);
                                    if (!empty($sImage)){
                                        $sImage = $sImage['src'];
                                    }else{
                                        $sImage = null;
                                    }

                                    $sDescriptionText = trim($arItem['PREVIEW_TEXT']);
                                    $sDescriptionText = TruncateText($sDescriptionText, 300);

                                    $bImageShow = $arResult['VIEW_PARAMETERS']['PICTURE_SHOW'] && !empty($sImage);
                                    $bDescriptionShow = $arResult['VIEW_PARAMETERS']['DESCRIPTION_SHOW'] && !empty($sDescriptionText);
                                ?>
                                    <div class="news-list-element <?=$bImageShow?"":"no-image"?>">
                                        <div class="news-list-element-wrapper" id="<?= $sAreaId ?>">
                                            <?php if ($bImageShow){?>
                                                <div class="news-list-element-image-wrapper">
                                                    <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>" class="news-list-element-image intec-image-effect" style="background-image: url('<?= $sImage ?>')"></a>
                                                </div>
                                            <?php }?>
                                            <div class="news-list-element-content">
                                                <div class="news-list-element-content-wrapper">
                                                    <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>" class="news-list-element-name"><?= $arItem['NAME'] ?></a>
                                                    <?php if ($bDescriptionShow){ ?>
                                                    <div class="news-list-element-description">
                                                        <?= $sDescriptionText;?>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                                <div class="intec-aligner"></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php $bSectionFirst = false ?>
                <?php } ?>
            </div>
            <?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
                <br /><?=$arResult["NAV_STRING"]?>
            <?endif;?>
        </div>
    </div>
</div>
