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

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-catalog-section',
        'c-catalog-section-services-tile-2'
    ],
    'data' => [
        'columns' => $arVisual['COLUMNS'],
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
    <?= Html::beginTag('div', [
        'class' => [
            'catalog-section-items',
            'intec-grid' => [
                '',
                'wrap',
                'a-h-start',
                'a-v-stretch',
                'i-10'
            ]
        ],
        'data' => [
            'entity' => $sTemplateContainer
        ]
    ]) ?>
        <?php foreach ($arResult['ITEMS'] as $arItem) { ?>
        <?php
            $sId = $sTemplateId.'_'.$arItem['ID'];
            $sAreaId = $this->GetEditAreaId($sId);
            $this->AddEditAction($sId, $arItem['EDIT_LINK']);
            $this->AddDeleteAction($sId, $arItem['DELETE_LINK']);

            $sName = $arItem['NAME'];
            $sLink = $arItem['DETAIL_PAGE_URL'];
            $sDescription = null;
            $sPicture = $arItem['PREVIEW_PICTURE'];

            if ($arVisual['DESCRIPTION']['SHOW']) {
                $sDescription = $arItem['PREVIEW_TEXT'];

                if (!empty($sDescription))
                    $sDescription = Html::stripTags($sDescription);

                if (!empty($sDescription))
                    $sDescription = StringHelper::truncate($sDescription, 125);
            }

            if (!empty($sPicture)) {
                $sPicture = CFile::ResizeImageGet($sPicture, [
                    'width' => 600,
                    'height' => 600
                ], BX_RESIZE_IMAGE_PROPORTIONAL);

                if (!empty($sPicture))
                    $sPicture = $sPicture['src'];
            }

            if (empty($sPicture))
                $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

        ?>
            <?= Html::beginTag('div', [
                'class' => Html::cssClassFromArray([
                    'catalog-section-item' => true,
                    'intec-grid-item' => [
                        $arVisual['COLUMNS'] => true,
                        '1050-2' => !$arVisual['WIDE'] && $arVisual['COLUMNS'] >= 3,
                        '750-1' => !$arVisual['WIDE'],
                        '720-2' => !$arVisual['WIDE'],
                        '1000-3' => $arVisual['WIDE'] && $arVisual['COLUMNS'] >= 4,
                        '750-2' => $arVisual['WIDE'] && $arVisual['COLUMNS'] >= 3,
                        '450-1' => true
                    ]
                ], true),
                'data' => [
                    'entity' => 'items-row'
                ]
            ]) ?>
                <div id="<?= $sAreaId ?>" class="catalog-section-item-wrapper">
                    <?= Html::beginTag('div', [
                        'class' => 'catalog-section-item-picture',
                        'data' => [
                            'type' => $arVisual['PICTURE']['TYPE'],
                            'indents' => $arVisual['PICTURE']['INDENTS'] ? 'true' : 'false'
                        ]
                    ]) ?>
                        <a href="<?= $sLink ?>" style="background-image: url('<?= $sPicture ?>');"></a>
                    <?= Html::endTag('div') ?>
                    <div class="catalog-section-item-information">
                        <div class="catalog-section-item-name" data-alignment="<?= $arVisual['NAME']['POSITION'] ?>">
                            <a href="<?= $sLink ?>"><?= $sName ?></a>
                        </div>
                        <?php if (!empty($sDescription)) { ?>
                            <div class="catalog-section-item-description" data-alignment="<?= $arVisual['DESCRIPTION']['POSITION'] ?>">
                                <?= $sDescription ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?= Html::endTag('div') ?>
        <?php } ?>
    <?= Html::endTag('div') ?>
    <!-- items-container -->
    <?php if ($arVisual['NAVIGATION']['LAZY']['BUTTON']) { ?>
        <!-- noindex -->
        <div class="catalog-section-more" data-use="show-more-<?= $arNavigation['NavNum'] ?>">
            <div class="catalog-section-more-button">
                <div class="catalog-section-more-icon intec-cl-background">
                    <i class="glyph-icon-show-more"></i>
                </div>
                <div class="catalog-section-more-text intec-cl-text">
                    <?= Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_2_LAZY_TEXT') ?>
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