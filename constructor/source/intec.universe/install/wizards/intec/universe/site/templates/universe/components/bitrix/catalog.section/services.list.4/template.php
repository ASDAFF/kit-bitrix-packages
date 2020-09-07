<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;

/**
 * @var $arResult
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);

if (empty($arResult['ITEMS']))
    return;

$arNavigation = !empty($arResult['NAV_RESULT']) ? [
    'NavPageCount' => $arResult['NAV_RESULT']->NavPageCount,
    'NavPageNomer' => $arResult['NAV_RESULT']->NavPageNomer,
    'NavNum' => $arResult['NAV_RESULT']->NavNum
] : [
    'NavPageCount' => 1,
    'NavPageNomer' => 1,
    'NavNum' => $this->randString()
];

$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
$sTemplateContainer = $sTemplateId.'-'.$arNavigation['NavNum'];
$arVisual = $arResult['VISUAL'];
$arVisual['NAVIGATION']['LAZY']['BUTTON'] =
    $arVisual['NAVIGATION']['LAZY']['BUTTON'] &&
    $arNavigation['NavPageNomer'] < $arNavigation['NavPageCount'];

$iCounter = 0;

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-catalog-section',
        'c-catalog-section-services-list-4'
    ],
    'data' => [
        'wide' => $arVisual['WIDE'] ? 'true' : 'false'
    ]
]) ?>
    <?php if ($arVisual['NAVIGATION']['TOP']['SHOW']) { ?>
        <div class="catalog-section-navigation catalog-section-navigation-top" data-pagination-num="<?= $arNavigation['NavNum'] ?>">
            <!-- pagination-container -->
            <?= $arResult['NAV_STRING'] ?>
            <!-- pagination-container -->
        </div>
    <?php } ?>
    <!-- items-container -->
    <div class="catalog-section-items" data-entity="<?= $sTemplateContainer ?>">
        <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
        <?php
            $sId = $sTemplateId.'_'.$arItem['ID'];
            $sAreaId = $this->GetEditAreaId($sId);
            $this->AddEditAction($sId, $arItem['EDIT_LINK']);
            $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

            $sName = $arItem['NAME'];
            $sLink = $arItem['DETAIL_PAGE_URL'];
            $sDescription = $arItem['PREVIEW_TEXT'];
            $sPicture = $arItem['PREVIEW_PICTURE'];
            $arData = $arItem['DATA'];

            if (!empty($sDescription))
                $sDescription = Html::stripTags($sDescription);

            if (!empty($sDescription))
                $sDescription = StringHelper::truncate($sDescription, 300);

            if (!empty($sPicture)) {
                $sPicture = CFile::ResizeImageGet($sPicture, [
                    'width' => 450,
                    'height' => 450
                ], BX_RESIZE_IMAGE_PROPORTIONAL);

                if (!empty($sPicture))
                    $sPicture = $sPicture['src'];
            }

            if (empty($sPicture))
                $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';
        ?>
            <div class="catalog-section-item" data-entity="items-row">
                <?= Html::beginTag('div', [
                    'id' => $sAreaId,
                    'class' =>  Html::cssClassFromArray([
                        'catalog-section-item-wrapper' => true,
                        'intec-grid' => true,
                        'intec-grid-wrap' => true,
                        'intec-grid-a-h-center' => true,
                        'intec-grid-i-10' => true,
                        'intec-grid-o-horizontal-reverse' => $iCounter % 2
                    ], true)
                ]); ?>
                    <div class="catalog-section-item-picture intec-grid-item-auto intec-grid-item-700-1">
                        <div class="catalog-section-item-picture-wrapper">
                            <?= Html::tag('a', null, [
                                'href' => $sLink,
                                'class' => 'intec-image-effect',
                                'style' => [
                                    'background-image' => 'url(\''.$sPicture.'\')'
                                ]
                            ]) ?>
                        </div>
                    </div>
                    <div class="catalog-section-item-information intec-grid-item intec-grid-item-700-1">
                        <?php if (!empty($arData['SECTION']['NAME'])) { ?>
                            <div class="catalog-section-item-section-wrap">
                                <a href="<?= $arData['SECTION']['LINK'] ?>" class="catalog-section-item-section intec-cl-text-hover">
                                    <?= $arData['SECTION']['NAME'] ?>
                                </a>
                            </div>
                        <?php } ?>
                        <a href="<?= $sLink ?>" class="catalog-section-item-name intec-cl-text-hover">
                            <?= $sName ?>
                        </a>
                        <?php if (!empty($sDescription)) { ?>
                            <div class="catalog-section-item-description">
                                <?= $sDescription ?>
                            </div>
                        <?php } ?>
                        <div class="catalog-section-item-price-wrap">
                            <?php if ($arVisual['PRICE']['SHOW'] && !empty($arData['PRICE']['VALUE'])) { ?>
                                <div class="catalog-section-item-price-title">
                                    <?= Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_4_PRICE_TITLE') ?>
                                </div>
                            <?php } ?>
                            <div class="intec-grid intec-grid-wrap intec-grid-i-20 intec-grid-a-v-center">
                                <?php if ($arVisual['PRICE']['SHOW'] && !empty($arData['PRICE']['VALUE'])) { ?>
                                    <div class="catalog-section-item-price intec-grid-item-auto intec-grid-item-500-1">
                                        <?= $arData['PRICE']['VALUE'] ?>
                                    </div>
                                <?php } ?>
                                <div class="catalog-section-item-btn-more-wrap intec-grid-item intec-grid-item-500-1">
                                    <a href="<?= $sLink ?>" class="catalog-section-item-btn-more intec-ui intec-ui-control-button intec-ui-scheme-current intec-ui-mod-transparent intec-ui-mod-round-half intec-ui-size-5">
                                        <?= Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_4_BTN_MORE') ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?= Html::endTag('div') ?>
            </div>
            <?php $iCounter++ ?>
        <?php } ?>
    </div>
    <!-- items-container -->
    <?php if ($arVisual['NAVIGATION']['LAZY']['BUTTON']) { ?>
        <!-- noindex -->
        <div class="catalog-section-more" data-use="show-more-<?= $arNavigation['NavNum'] ?>">
            <div class="catalog-section-more-button">
                <div class="catalog-section-more-icon intec-cl-background">
                    <i class="glyph-icon-show-more"></i>
                </div>
                <div class="catalog-section-more-text intec-cl-text">
                    <?= Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_4_LAZY_TEXT') ?>
                </div>
            </div>
        </div>
        <!-- /noindex -->
    <?php } ?>
    <?php if ($arVisual['NAVIGATION']['BOTTOM']['SHOW']) { ?>
        <div class="catalog-section-navigation catalog-section-navigation-bottom" data-pagination-num="<?= $arNavigation['NavNum'] ?>">
            <!-- pagination-container -->
            <?= $arResult['NAV_STRING'] ?>
            <!-- pagination-container -->
        </div>
    <?php } ?>
    <?php include(__DIR__.'/parts/script.php') ?>
<?= Html::endTag('div') ?>