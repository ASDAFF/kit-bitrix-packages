<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 */

if (!Loader::includeModule('iblock'))
    return;

if (!Loader::includeModule('intec.core'))
    return;

$this->setFrameMode(true);

$arParams = ArrayHelper::merge([
    'SECTIONS_CHILDREN_SECTION_DESCRIPTION_SHOW' => 'Y',
    'SECTIONS_CHILDREN_SECTION_DESCRIPTION_POSITION' => 'top',
    'SECTIONS_CHILDREN_CANONICAL_URL_USE' => 'N',
    'SECTIONS_CHILDREN_CANONICAL_URL_TEMPLATE' => null,
    'SECTIONS_CHILDREN_MENU_SHOW' => 'N'
], $arParams);

$arSection = $arResult['SECTION'];
$arCanonicalUrl = [
    'USE' => $arParams['SECTIONS_CHILDREN_CANONICAL_URL_USE'] === 'Y',
    'TEMPLATE' => $arParams['SECTIONS_CHILDREN_CANONICAL_URL_TEMPLATE']
];

if (empty($arCanonicalUrl['TEMPLATE']) || empty($arSection))
    $arCanonicalUrl['USE'] = false;

$arDescription = [
    'SHOW' => $arParams['SECTIONS_CHILDREN_SECTION_DESCRIPTION_SHOW'] === 'Y',
    'POSITION' => ArrayHelper::fromRange([
        'top',
        'bottom'
    ], $arParams['SECTIONS_CHILDREN_SECTION_DESCRIPTION_POSITION']),
    'VALUE' => !empty($arSection) ? $arSection['DESCRIPTION'] : null
];

if (empty($arDescription['VALUE']))
    $arDescription['SHOW'] = false;

$sLevel = 'CHILDREN';

include(__DIR__.'/parts/menu.php');
include(__DIR__.'/parts/sections.php');
include(__DIR__.'/parts/elements.php');

$arMenu['SHOW'] = $arMenu['SHOW'] && $arParams['SECTIONS_CHILDREN_MENU_SHOW'] === 'Y';

$arColumns = [
    'SHOW' => $arMenu['SHOW']
];

if ($arColumns['SHOW']) {
    $arSections['PARAMETERS']['WIDE'] = 'N';
    $arElements['PARAMETERS']['WIDE'] = 'N';
}

if ($arCanonicalUrl['USE'])
    $APPLICATION->SetPageProperty('canonical', CIBlock::ReplaceSectionUrl(
        $arCanonicalUrl['TEMPLATE'],
        $arSection,
        true,
        'S'
    ));

?>
<?php if ($arResult['CONTENT']['SECTIONS']['BEGIN']['SHOW']) { ?>
    <?php $APPLICATION->IncludeComponent(
        'bitrix:main.include',
        '.default',
        [
            'AREA_FILE_SHOW' => 'file',
            'PATH' => $arResult['CONTENT']['SECTIONS']['BEGIN']['PATH'],
            'EDIT_TEMPLATE' => ''
        ],
        $component
    ) ?>
<?php } ?>
<div class="ns-bitrix c-catalog c-catalog-services-1 p-section">
    <div class="catalog-wrapper intec-content intec-content-visible">
        <div class="catalog-wrapper-2 intec-content-wrapper">
            <div class="catalog-content">
                <?php if ($arColumns['SHOW']) { ?>
                    <div class="catalog-content-left intec-content-left">
                        <?php if ($arMenu['SHOW']) { ?>
                            <div class="catalog-menu">
                                <?php $APPLICATION->IncludeComponent(
                                    'bitrix:menu',
                                    $arMenu['TEMPLATE'],
                                    $arMenu['PARAMETERS'],
                                    $component
                                ) ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="catalog-content-right intec-content-right">
                        <div class="catalog-content-right-wrapper intec-content-right-wrapper">
                <?php } ?>
                <?php if ($arDescription['SHOW'] && $arDescription['POSITION'] === 'top') { ?>
                    <div class="<?= Html::cssClassFromArray([
                        'catalog-description',
                        'catalog-description-'.$arDescription['POSITION'],
                        'intec-ui-markup-text'
                    ]) ?>"><?= $arDescription['VALUE'] ?></div>
                <?php } ?>
                <?php if ($arElements['SHOW'] && $arElements['POSITION'] === 'top') { ?>
                    <?php $APPLICATION->IncludeComponent(
                        'bitrix:catalog.section',
                        $arElements['TEMPLATE'],
                        $arElements['PARAMETERS'],
                        $component
                    ); ?>
                <?php } ?>
                <?php if ($arSections['SHOW']) { ?>
                    <?php $APPLICATION->IncludeComponent(
                        'bitrix:catalog.section.list',
                        $arSections['TEMPLATE'],
                        $arSections['PARAMETERS'],
                        $component
                    ); ?>
                <?php } ?>
                <?php if ($arElements['SHOW'] && $arElements['POSITION'] === 'bottom' || !$arElements['SHOW'] && !empty($arElements['TEMPLATE'])) { ?>
                    <?php $APPLICATION->IncludeComponent(
                        'bitrix:catalog.section',
                        $arElements['TEMPLATE'],
                        $arElements['PARAMETERS'],
                        $component
                    ); ?>
                <?php } ?>
                <?php if ($arDescription['SHOW'] && $arDescription['POSITION'] === 'bottom') { ?>
                    <div class="<?= Html::cssClassFromArray([
                        'catalog-description',
                        'catalog-description-'.$arDescription['POSITION'],
                        'intec-ui-markup-text'
                    ]) ?>"><?= $arDescription['VALUE'] ?></div>
                <?php } ?>
                <?php if ($arColumns['SHOW']) { ?>
                        </div>
                    </div>
                    <div class="intec-ui-clear"></div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<?php if ($arResult['CONTENT']['SECTIONS']['END']['SHOW']) { ?>
    <?php $APPLICATION->IncludeComponent(
        'bitrix:main.include',
        '.default',
        [
            'AREA_FILE_SHOW' => 'file',
            'PATH' => $arResult['CONTENT']['SECTIONS']['END']['PATH'],
            'EDIT_TEMPLATE' => ''
        ],
        $component
    ) ?>
<?php } ?>