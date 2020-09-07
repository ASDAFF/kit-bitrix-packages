<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
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

$arForm = $arResult['FORM'];
$arForm['PARAMETERS'] = [
    'id' => $arForm['ID'],
    'template' => $arForm['TEMPLATE'],
    'parameters' => [
        'AJAX_OPTION_ADDITIONAL' => $sTemplateId.'_FORM_ORDER',
        'CONSENT_URL' => $arForm['CONSENT']
    ],
    'settings' => [
        'title' => $arForm['TITLE']
    ],
    'fields' => [
        $arForm['FIELD'] => null
    ]
];

if (empty($arForm['BUTTON']))
    $arForm['BUTTON'] = Loc::getMessage('C_MAIN_SERVICES_TEMPLATE_11_TEMPLATE_ODER_BUTTON_DEFAULT');

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-catalog-section',
        'c-catalog-section-services-tile-3'
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
                'i-4'
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
            $arData = $arItem['DATA'];
            $sDescription = null;

            if ($arVisual['DESCRIPTION']['SHOW']) {
                $sDescription = $arItem['PREVIEW_TEXT'];

                if (!empty($sDescription))
                    $sDescription = Html::stripTags($sDescription);

                /*if (!empty($sDescription))
                    $sDescription = StringHelper::truncate($sDescription, 125);*/
            }

            $arForm['PARAMETERS']['fields'][$arForm['FIELD']] = $arItem['NAME'];
        ?>
            <?= Html::beginTag('div', [
                'class' => Html::cssClassFromArray([
                    'catalog-section-item-wrap' => true,
                    'intec-grid-item' => [
                        $arVisual['COLUMNS'] => true,
                        '1050-2' => !$arVisual['WIDE'] && $arVisual['COLUMNS'] >= 3,
                        '768-1' => !$arVisual['WIDE'],
                        '720-2' => !$arVisual['WIDE'],
                        '1000-3' => $arVisual['WIDE'] && $arVisual['COLUMNS'] >= 4,
                        '768-2' => $arVisual['WIDE'] && $arVisual['COLUMNS'] >= 3,
                        '450-1' => true
                    ]
                ], true),
                'data' => [
                    'entity' => 'items-row'
                ]
            ]) ?>
                <div id="<?= $sAreaId ?>" class="catalog-section-item" data-role="item" data-expanded="false">
                    <div class="catalog-section-item-wrapper">
                        <div class="catalog-section-item-information">
                            <?php if (!empty($arData['CATEGORY']['VALUE']) && $arVisual['CATEGORY']['SHOW']) { ?>
                                <div class="catalog-section-item-category" data-alignment="<?= $arVisual['CATEGORY']['POSITION'] ?>">
                                    <?= $arData['CATEGORY']['VALUE'] ?>
                                </div>
                            <?php } ?>
                            <div class="catalog-section-item-name-wrap" data-alignment="<?= $arVisual['NAME']['POSITION'] ?>">
                                <a class="catalog-section-item-name intec-cl-text-hover" href="<?= $sLink ?>"><?= $sName ?></a>
                            </div>
                            <?php if (!empty($sDescription)) { ?>
                                <div class="catalog-section-item-description" data-alignment="<?= $arVisual['DESCRIPTION']['POSITION'] ?>">
                                    <?= $sDescription ?>
                                </div>
                            <?php } ?>
                            <?php if (!empty($arData['PRICE']) && $arVisual['PRICE']['SHOW']) { ?>
                                <div class="catalog-section-item-price" data-alignment="<?= $arVisual['PRICE']['POSITION'] ?>">
                                    <?= $arData['PRICE'] ?>
                                </div>
                            <?php } ?>

                            <?php if ($arForm['USE']) { ?>
                                <div class="catalog-section-item-button-wrap" data-alignment="<?= $arVisual['ORDER_BUTTON']['POSITION'] ?>">
                                    <?= Html::tag('div', Html::stripTags($arForm['BUTTON']), [
                                        'class' => [
                                            'catalog-section-item-button',
                                            'intec-ui' => [
                                                '',
                                                'control-button',
                                                'scheme-current',
                                                'mod-transparent',
                                                'mod-round-half',
                                                'size-4',
                                            ]
                                        ],
                                        'onclick' => '(function() {
                                            universe.forms.show('.JavaScript::toObject($arForm['PARAMETERS']).');
                                            
                                            if (window.yandex && window.yandex.metrika) {
                                                window.yandex.metrika.reachGoal(\'forms.open\');
                                                window.yandex.metrika.reachGoal('.JavaScript::toObject('forms.'.$arForm['PARAMETERS']['id'].'.open').');
                                            }
                                        })()'
                                    ]) ?>
                                </div>
                            <?php } ?>
                        </div>
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
                    <?= Loc::getMessage('C_BITRIX_CATALOG_SECTION_SERVICES_TILE_3_LAZY_TEXT') ?>
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