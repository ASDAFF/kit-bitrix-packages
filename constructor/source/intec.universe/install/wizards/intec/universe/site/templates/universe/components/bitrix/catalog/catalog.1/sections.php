<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Loader;
use intec\Core;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
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

$oRequest = Core::$app->request;
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
$bIsBase = Loader::includeModule('catalog') && Loader::includeModule('sale');
$bIsLite = !$bIsBase && Loader::includeModule('intec.startshop');
$bIsAjax = false;

$arParams = ArrayHelper::merge([
    'SECTIONS_ROOT_SECTION_DESCRIPTION_SHOW' => 'Y',
    'SECTIONS_ROOT_SECTION_DESCRIPTION_POSITION' => 'top',
    'SECTIONS_ROOT_CANONICAL_URL_USE' => 'N',
    'SECTIONS_ROOT_CANONICAL_URL_TEMPLATE' => null,
    'SECTIONS_ROOT_MENU_SHOW' => 'N',
    'LIST_ROOT_SHOW' => 'N',
    'ROOT_LAYOUT' => '1'
], $arParams);

$sLayout = ArrayHelper::fromRange([1, 2], $arParams['ROOT_LAYOUT']);

include(__DIR__.'/parts/sort.php');

$arIBlock = $arResult['IBLOCK'];
$arSection = null;
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
include(__DIR__.'/parts/tags.php');
include(__DIR__.'/parts/filter.php');

$arFilter['SHOW'] = false;

include(__DIR__.'/parts/sections.php');
include(__DIR__.'/parts/elements.php');

$arMenu['SHOW'] = $arMenu['SHOW'] && $arParams['SECTIONS_ROOT_MENU_SHOW'] === 'Y';
$arElements['SHOW'] = $arElements['SHOW'] && $arParams['LIST_ROOT_SHOW'] === 'Y';

$arColumns = [
    'SHOW' => $arMenu['SHOW'] || ($arFilter['SHOW'] && $arFilter['TYPE'] === 'vertical')
];

if ($arColumns['SHOW']) {
    $arSections['PARAMETERS']['WIDE'] = 'N';
    $arElements['PARAMETERS']['WIDE'] = 'N';
}

if ($arParams['ROOT_LAYOUT'] == 2) {
    $arSections['PARAMETERS']['WIDE'] = 'Y';
}

if ($arCanonicalUrl['USE'])
    $APPLICATION->SetPageProperty('canonical', StringHelper::replaceMacros($arCanonicalUrl['TEMPLATE'], [
        'SITE_DIR' => SITE_DIR,
        'SERVER_NAME' => SITE_SERVER_NAME
    ]));

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-catalog',
        'c-catalog-catalog-1',
        'p-sections'
    ],
    'data' => [
        'layout' => $arParams['ROOT_LAYOUT']
    ]
]) ?>
    <div class="catalog-wrapper intec-content intec-content-visible">
        <div class="catalog-wrapper-2 intec-content-wrapper">
            <?php if ($arFilter['SHOW'] && $arFilter['TYPE'] === 'horizontal') { ?>
                <?php if ($arTags['SHOW']) { ?>
                    <?php $arTags['SHOW'] = false ?>
                    <?php $APPLICATION->IncludeComponent(
                        'intec.universe:tags.list',
                        $arTags['TEMPLATE'],
                        $arTags['PARAMETERS'],
                        $component
                    ) ?>
                <?php } ?>
                <?php $APPLICATION->IncludeComponent(
                    'bitrix:catalog.smart.filter',
                    $arFilter['TEMPLATE'],
                    $arFilter['PARAMETERS'],
                    $component
                ) ?>
            <?php } ?>

            <?php if ($sLayout === '2') { ?>
                <?php if ($arDescription['SHOW'] && $arDescription['POSITION'] === 'top') { ?>
                    <div class="<?= Html::cssClassFromArray([
                        'catalog-description',
                        'catalog-description-'.$arDescription['POSITION'],
                        'intec-ui-markup-text'
                    ]) ?>"><?= $arDescription['VALUE'] ?></div>
                <?php } ?>
                <?php if ($arSections['SHOW']) { ?>
                    <?php $APPLICATION->IncludeComponent(
                        'bitrix:catalog.section.list',
                        $arSections['TEMPLATE'],
                        $arSections['PARAMETERS'],
                        $component
                    ); ?>
                <?php } ?>
            <?php } ?>

            <?= Html::beginTag('div', [
                'class' => 'catalog-content',
                'data' => [
                    'role' => !$arColumns['SHOW'] ? 'content' : null
                ]
            ]) ?>
                <?php if ($arColumns['SHOW']) { ?>
                    <div class="catalog-content-left intec-content-left">
                        <?php if ($arFilter['SHOW'] && $arFilter['TYPE'] === 'vertical') { ?>
                                <?php $APPLICATION->IncludeComponent(
                                    'bitrix:catalog.smart.filter',
                                    $arFilter['TEMPLATE'],
                                    $arFilter['PARAMETERS'],
                                    $component
                                ) ?>
                        <?php } ?>
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
                        <div class="catalog-content-right-wrapper intec-content-right-wrapper" data-role="content">
                <?php } ?>
                <?php if ($sLayout === '1') { ?>
                    <?php if ($arTags['SHOW']) { ?>
                        <?php $APPLICATION->IncludeComponent(
                            'intec.universe:tags.list',
                            $arTags['TEMPLATE'],
                            $arTags['PARAMETERS'],
                            $component
                        ) ?>
                    <?php } ?>
                    <?php if ($arDescription['SHOW'] && $arDescription['POSITION'] === 'top') { ?>
                        <div class="<?= Html::cssClassFromArray([
                            'catalog-description',
                            'catalog-description-'.$arDescription['POSITION'],
                            'intec-ui-markup-text'
                        ]) ?>"><?= $arDescription['VALUE'] ?></div>
                    <?php } ?>
                    <?php if ($arSections['SHOW']) { ?>
                        <?php $APPLICATION->IncludeComponent(
                            'bitrix:catalog.section.list',
                            $arSections['TEMPLATE'],
                            $arSections['PARAMETERS'],
                            $component
                        ); ?>
                    <?php } ?>
                <?php } ?>
                <?php if ($arElements['SHOW'] || !$arElements['SHOW'] && !empty($arElements['TEMPLATE']) && $arParams['LIST_ROOT_SHOW'] === 'Y') { ?>
                    <?php include(__DIR__.'/parts/panel.php') ?>
                    <?php
                        foreach ($arSort['PROPERTIES'] as $arSortProperty) {
                            if ($arSortProperty['ACTIVE']) {
                                $arElements['PARAMETERS']['ELEMENT_SORT_FIELD'] = $arSortProperty['FIELD'];
                                $arElements['PARAMETERS']['ELEMENT_SORT_ORDER'] = $arSort['ORDER'];

                                break;
                            }
                        }

                        unset($arSortProperty);
                    ?>
                    <?php $APPLICATION->IncludeComponent(
                        'bitrix:catalog.section',
                        $arElements['TEMPLATE'],
                        $arElements['PARAMETERS'],
                        $component
                    ) ?>
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
    <script type="text/javascript">
        (function () {
            var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
            var filter = $('[data-role="filter"]', root);
            var content = $('[data-role="content"]', root);

            filter.state = false;
            filter.button = $('[data-role="filter.button"]', root);
            filter.button.on('click', function () {
                if (filter.state) {
                    filter.hide();
                } else {
                    filter.show();
                }

                filter.state = !filter.state;
            });

            content.refresh = function (url) {
                if (url == null)
                    url = null;

                $.ajax({
                    'url': url,
                    'data': {
                        'catalog': {
                            'ajax': 'Y'
                        }
                    },
                    'cache': false,
                    'success': function (response) {
                        content.html(response);
                        universe.basket.update();
                    }
                });
            };

            <?php if ($arFilter['SHOW'] && $arFilter['AJAX']) { ?>
                if (smartFilter && smartFilter.on)
                    smartFilter.on('refresh', function (event, url) {
                        if (window.history.enabled || window.history.pushState != null) {
                            window.history.pushState(null, document.title, url);
                        } else {
                            window.location.href = url;
                        }

                        content.refresh(url);
                    });
            <?php } ?>
        })();
    </script>
<?= Html::endTag('div') ?>