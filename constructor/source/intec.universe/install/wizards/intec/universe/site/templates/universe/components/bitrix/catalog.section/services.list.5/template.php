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

$iCounter = 1;

/**
 * @var Closure $vItems()
 */
include (__DIR__.'/parts/items.php');

?>
<?php if ($arVisual['WIDE']) { ?>
            </div>
        </div>
    </div>
<?php } ?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-catalog-section',
        'c-catalog-section-services-list-5'
    ],
    'data' => [
        'wide' => $arVisual['WIDE'] ? 'true' : 'false'
    ]
]) ?>
    <?php if ($arVisual['NAVIGATION']['TOP']['SHOW']) { ?>
        <div class="intec-content">
            <div class="intec-content-wrapper">
                <div class="catalog-section-navigation catalog-section-navigation-top" data-pagination-num="<?= $arNavigation['NavNum'] ?>">
                    <!-- pagination-container -->
                    <?= $arResult['NAV_STRING'] ?>
                    <!-- pagination-container -->
                </div>
            </div>
        </div>
    <?php } ?>
    <!-- items-container -->
    <div class="catalog-section-items" data-entity="<?= $sTemplateContainer ?>">
        <?php if ($arVisual['TABS']['USE'] && !empty($arResult['TABS'])) {
            include(__DIR__.'/parts/tabs.php');
        } else {
            $vItems($arResult['ITEMS']);
        } ?>
    </div>
    <!-- items-container -->
    <?php if ($arVisual['NAVIGATION']['LAZY']['BUTTON']) { ?>
        <!-- noindex -->
        <div class="intec-content">
            <div class="intec-content-wrapper">
                <div class="catalog-section-more" data-use="show-more-<?= $arNavigation['NavNum'] ?>">
                    <div class="catalog-section-more-button">
                        <div class="catalog-section-more-icon intec-cl-background">
                            <i class="glyph-icon-show-more"></i>
                        </div>
                        <div class="catalog-section-more-text intec-cl-text">
                            <?= Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_LIST_5_TEMPLATE_LAZY_TEXT') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /noindex -->
    <?php } ?>
    <?php if ($arVisual['NAVIGATION']['BOTTOM']['SHOW']) { ?>
        <div class="intec-content">
            <div class="intec-content-wrapper">
                <div class="catalog-section-navigation catalog-section-navigation-bottom" data-pagination-num="<?= $arNavigation['NavNum'] ?>">
                    <!-- pagination-container -->
                    <?= $arResult['NAV_STRING'] ?>
                    <!-- pagination-container -->
                </div>
            </div>
        </div>
    <?php } ?>
    <?php include(__DIR__.'/parts/script.php') ?>
<?= Html::endTag('div') ?>
<?php if ($arVisual['WIDE']) { ?>
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <div class="catalog-content">
<?php } ?>
