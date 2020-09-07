<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Loader;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\StringHelper;

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
    'SECTIONS_ROOT_SECTION_DESCRIPTION_SHOW' => 'Y',
    'SECTIONS_ROOT_SECTION_DESCRIPTION_POSITION' => 'top',
    'SECTIONS_ROOT_CANONICAL_URL_USE' => 'N',
    'SECTIONS_ROOT_CANONICAL_URL_TEMPLATE' => null,
    'SECTIONS_ROOT_MENU_SHOW' => 'N',
    'LIST_ROOT_SHOW' => 'N'
], $arParams);

$arIBlock = $arResult['IBLOCK'];
$arCanonicalUrl = [
    'USE' => $arParams['SECTIONS_ROOT_CANONICAL_URL_USE'] === 'Y',
    'TEMPLATE' => $arParams['SECTIONS_ROOT_CANONICAL_URL_TEMPLATE']
];

if (empty($arCanonicalUrl['TEMPLATE']))
    $arCanonicalUrl['USE'] = false;

$arDescription = [
    'SHOW' => $arParams['SECTIONS_ROOT_SECTION_DESCRIPTION_SHOW'] === 'Y',
    'POSITION' => ArrayHelper::fromRange([
        'top',
        'bottom'
    ], $arParams['SECTIONS_ROOT_SECTION_DESCRIPTION_POSITION']),
    'VALUE' => !empty($arIBlock) ? $arIBlock['DESCRIPTION'] : null
];

if (empty($arDescription['VALUE']))
    $arDescription['SHOW'] = false;

$sLevel = 'ROOT';

include(__DIR__.'/parts/menu.php');
include(__DIR__.'/parts/sections.php');
include(__DIR__.'/parts/elements.php');

$arMenu['SHOW'] = $arMenu['SHOW'] && $arParams['SECTIONS_ROOT_MENU_SHOW'] === 'Y';
$arElements['SHOW'] = $arElements['SHOW'] && $arParams['LIST_ROOT_SHOW'] === 'Y';

$arColumns = [
    'SHOW' => $arMenu['SHOW']
];

if ($arColumns['SHOW'])
    $arSections['PARAMETERS']['WIDE'] = 'N';

if ($arCanonicalUrl['USE'])
    $APPLICATION->SetPageProperty('canonical', StringHelper::replaceMacros($arCanonicalUrl['TEMPLATE'], [
        'SITE_DIR' => SITE_DIR,
        'SERVER_NAME' => SITE_SERVER_NAME
    ]));

?>
<?php if ($arResult['CONTENT']['ROOT']['BEGIN']['SHOW']) { ?>
    <?php $APPLICATION->IncludeComponent(
        'bitrix:main.include',
        '.default',
        [
            'AREA_FILE_SHOW' => 'file',
            'PATH' => $arResult['CONTENT']['ROOT']['BEGIN']['PATH'],
            'EDIT_TEMPLATE' => ''
        ],
        $component
    ) ?>
<?php } ?>
<div class="ns-bitrix c-catalog c-catalog-services-1 p-sections">
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
                <?php if ($arElements['SHOW'] && $arElements['POSITION'] === 'bottom' || !$arElements['SHOW'] && !empty($arElements['TEMPLATE']) && $arParams['LIST_ROOT_SHOW'] === 'Y') { ?>
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
<?php if ($arResult['CONTENT']['ROOT']['END']['SHOW']) { ?>
    <?php $APPLICATION->IncludeComponent(
        'bitrix:main.include',
        '.default',
        [
            'AREA_FILE_SHOW' => 'file',
            'PATH' => $arResult['CONTENT']['ROOT']['END']['PATH'],
            'EDIT_TEMPLATE' => ''
        ],
        $component
    ) ?>
<?php } ?>