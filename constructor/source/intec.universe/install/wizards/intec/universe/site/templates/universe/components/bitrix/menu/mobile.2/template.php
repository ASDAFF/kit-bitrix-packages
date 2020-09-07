<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

Loc::loadMessages(__FILE__);

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arParams = ArrayHelper::merge([
    'ADDRESS_SHOW' => 'N',
    'ADDRESS' => null,
    'PHONES_SHOW' => 'N',
    'PHONES' => null,
    'EMAIL_SHOW' => 'N',
    'EMAIL' => null,
    'LOGOTYPE_SHOW' => 'N',
    'LOGOTYPE' => null,
    'LOGOTYPE_LINK' => null,
    'REGIONALITY_USE' => 'N',
    'AUTHORIZATION_SHOW' => 'N',
    'PROFILE_URL' => null,
    'SOCIAL_SHOW' => 'N',
    'SOCIAL_VK' => null,
    'SOCIAL_INSTAGRAM' => null,
    'SOCIAL_FACEBOOK' => null,
    'SOCIAL_TWITTER' => null,
    'BORDER_SHOW' => 'Y',
    'INFORMATION_VIEW' => 'view.1'
], $arParams);

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/'
];

$arAddress = [
    'SHOW' => $arParams['ADDRESS_SHOW'] === 'Y',
    'VALUE' => $arParams['ADDRESS']
];

if (empty($arAddress['VALUE']))
    $arAddress['SHOW'] = false;

$arCity = [
    'SHOW' => $arParams['CITY_SHOW'] === 'Y',
    'VALUE' => $arParams['CITY']
];

if (empty($arCity['VALUE']))
    $arCity['SHOW'] = false;

$arAuthorization = [
    'SHOW' => $arParams['AUTHORIZATION_SHOW'] === 'Y',
    'VALUE' => $arParams['PROFILE_URL']
];

if (empty($arAuthorization['VALUE']))
    $arAuthorization['SHOW'] = false;

$arPhones = [
    'SHOW' => $arParams['PHONES_SHOW'] === 'Y',
    'VALUES' => $arParams['PHONES'],
    'SELECTED' => null
];

if (empty($arPhones['VALUES'])) {
    $arPhones['SHOW'] = false;
} else {
    if (Type::isArray($arPhones['VALUES'])) {
        $arPhones['VALUES'] = array_filter($arParams['PHONES']);

        foreach($arPhones['VALUES'] as $arPhone) {
            $arValues[] = [
                'DISPLAY' => $arPhone,
                'LINK' => StringHelper::replace($arPhone, [
                    '(' => '',
                    ')' => '',
                    ' ' => '',
                    '-' => ''
                ])
            ];
        }
        $arPhones['SELECTED'] = ArrayHelper::shift($arValues);

        $arPhones['VALUES'] = $arValues;
    } else {
        $arPhones['SELECTED'] = [
            'DISPLAY' => $arPhones['VALUES'],
            'LINK' => StringHelper::replace($arPhones['VALUES'], [
                '(' => '',
                ')' => '',
                ' ' => '',
                '-' => ''
            ])
        ];

        $arPhones['VALUES'] = [];
    }
}

$arEmail = [
    'SHOW' => $arParams['EMAIL_SHOW'] === 'Y',
    'VALUE' => $arParams['EMAIL']
];

if (empty($arEmail['VALUE']))
    $arEmail['SHOW'] = false;

$arSocial = [
    'SHOW' => $arParams['SOCIAL_SHOW'] === 'Y',
    'ITEMS' => []
];

$bSocialShow = false;

foreach ([
 'VK',
 'INSTAGRAM',
 'FACEBOOK',
 'TWITTER'
] as $sSocial) {
    $sValue = ArrayHelper::getValue($arParams, 'SOCIAL_'.$sSocial);
    $arSocialItem = [
        'SHOW' => !empty($sValue),
        'VALUE' => $sValue
    ];

    $bSocialShow = $bSocialShow || $arSocialItem['SHOW'];
    $arSocial['ITEMS'][$sSocial] = $arSocialItem;
}

$arSocial['SHOW'] = $arSocial['SHOW'] && $bSocialShow;

$arLogotype = [
    'SHOW' => $arParams['LOGOTYPE_SHOW'] === 'Y',
    'PATH' => $arParams['LOGOTYPE'],
    'LINK' => $arParams['LOGOTYPE_LINK']
];

if (empty($arLogotype['PATH'])) {
    $arLogotype['SHOW'] = false;
} else {
    $arLogotype['PATH'] = StringHelper::replaceMacros(
        $arLogotype['PATH'],
        $arMacros
    );
}

$sPrefix = 'SEARCH_';
$arSearchParams = [];

$sSearchType = 'popup';
$sSearchTemplate = 'popup.1';

foreach ($arParams as $sKey => $sValue)
    if (StringHelper::startsWith($sKey, $sPrefix)) {
        $sKey = StringHelper::cut($sKey, StringHelper::length($sPrefix));
        if ($sKey === 'TYPE') {
            if ($sValue === 'page')
                $sSearchTemplate = 'input.1';

            $sSearchType = $sValue;
            continue;
        }
        $arSearchParams[$sKey] = $sValue;
    }

$arInformationView = $arParams['INFORMATION_VIEW'];

$arRegionality = [
    'USE' => $arParams['REGIONALITY_USE'] === 'Y' && Loader::includeModule('intec.regionality')
];

?>
<?php $fRenderItem = function ($arItem, $iLevel, $arParent = null, $bActive = false) use (&$fRenderItem) {
    $sName = ArrayHelper::getValue($arItem, 'TEXT');
    $sLink = ArrayHelper::getValue($arItem, 'LINK');
    $arChildren = ArrayHelper::getValue($arItem, 'ITEMS');

    $bSelected = ArrayHelper::getValue($arItem, 'SELECTED');
    $bSelected = Type::toBoolean($bSelected);
    $bHasChildren = !empty($arChildren);

    $bActive = $arItem['ACTIVE'];
    $sTag = $bHasChildren || $bActive ? 'div' : 'a';
?>
    <?= Html::beginTag('div', [
        'class' => Html::cssClassFromArray([
            'menu-item' => [
                '' => true,
                'level-'.$iLevel => true,
                'selected' => $bSelected
            ]
        ], true),
        'data' => [
            'role' => 'item',
            'level' => $iLevel,
            'expanded' => 'false',
            'current' => 'false'
        ]
    ]) ?>
        <div class="menu-item-wrapper">
            <?= Html::beginTag($sTag, [
                'class' => Html::cssClassFromArray([
                    'menu-item-content' => true,
                    'intec-cl' => [
                        'text' => $bSelected,
                        'text-hover' => true
                    ]
                ], true),
                'href' => $sTag == 'a' ? $sLink : null,
                'data' => [
                    'action' => $bHasChildren ? 'menu.item.open' : 'menu.close'
                ]
            ]) ?>
                <div class="intec-grid intec-grid-nowrap intec-grid-i-h-10 intec-grid-a-v-center">
                    <div class="menu-item-text-wrap intec-grid-item intec-grid-item-shrink-1">
                        <div class="menu-item-text">
                            <?= $sName ?>
                        </div>
                    </div>
                    <?php if ($bHasChildren) { ?>
                        <div class="menu-item-icon-wrap intec-grid-item-auto">
                            <div class="menu-item-icon">
                                <i class="far fa-angle-right"></i>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?= Html::endTag($sTag) ?>
            <?php if ($bHasChildren) {

                $sChildTag = $bActive ? 'div' : 'a';

            ?>
                <div class="menu-item-items" data-role="items">
                    <?= Html::beginTag('div', [
                        'class' => [
                            'menu-item' => [
                                '',
                                'level-'.($iLevel + 1),
                                'button'
                            ]
                        ],
                        'data' => [
                            'level' => $iLevel + 1
                        ]
                    ]) ?>
                        <div class="menu-item-wrapper">
                            <div class="menu-item-content intec-cl-text-hover" data-action="menu.item.close">
                                <div class="intec-grid intec-grid-nowrap intec-grid-i-h-10 intec-grid-a-v-center">
                                    <div class="menu-item-icon-wrap intec-grid-item-auto">
                                        <div class="menu-item-icon">
                                            <i class="far fa-angle-left"></i>
                                        </div>
                                    </div>
                                    <div class="menu-item-text-wrap intec-grid-item intec-grid-item-shrink-1">
                                        <div class="menu-item-text">
                                            <?= Loc::getMessage('C_MENU_MOBILE_2_BACK') ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?= Html::endTag('div') ?>
                    <?= Html::beginTag('div', [
                        'class' => [
                            'menu-item' => [
                                '',
                                'level-'.($iLevel + 1),
                                'title'
                            ]
                        ],
                        'data' => [
                            'level' => $iLevel + 1
                        ]
                    ]) ?>
                        <div class="menu-item-wrapper">
                            <?= Html::tag($sChildTag, $sName, [
                                'class' => 'menu-item-content',
                                'href' => $sChildTag == 'a' ? $sLink : null,
                                'data' => [
                                    'action' => 'menu.close'
                                ]
                            ]) ?>
                        </div>
                    <?= Html::endTag('div') ?>
                    <?php foreach ($arChildren as $arChild) {
                        $fRenderItem($arChild, $iLevel + 1, $arItem, $bActive);
                    } ?>
                </div>
            <?php } ?>
        </div>
    <?= Html::endTag('div') ?>
<?php } ?>
<?php if (!empty($arResult)) { ?>
    <div id="<?= $sTemplateId ?>" class="ns-bitrix c-menu c-menu-mobile-2">
        <div class="menu-button intec-cl-text-hover" data-action="menu.open">
            <i class="menu-button-icon glyph-icon-menu-icon"></i>
        </div>
        <?= Html::beginTag('div', [
            'class' => 'menu',
            'data' => [
                'role' => 'menu',
                'information' => $arInformationView,
                'search-type' => $sSearchType,
                'border-show' => $arParams['BORDER_SHOW'] === 'Y' ? 'true' : 'false',
                'regionality' => $arRegionality['USE'] ? 'true' : 'false'
            ]
        ]) ?>
            <div class="menu-panel">
                <div class="menu-panel-wrapper intec-grid intec-grid-nowrap intec-grid-i-h-10 intec-grid-a-v-center">
                    <div class="menu-panel-button-wrap intec-grid-item-auto">
                        <div class="menu-panel-button intec-cl-text-hover" data-action="menu.close">
                            <i class="glyph-icon-cancel"></i>
                        </div>
                    </div>
                    <?php if ($arLogotype['SHOW']) { ?>
                        <div class="menu-panel-logotype-wrap intec-grid-item">
                            <?= Html::beginTag(!empty($arLogotype['LINK']) ? 'a' : 'div', [
                                'class' => [
                                    'menu-panel-logotype',
                                    'intec-image'
                                ],
                                'href' => !empty($arLogotype['LINK']) ? $arLogotype['LINK'] : null
                            ]) ?>
                            <div class="intec-aligner"></div>
                            <?php $APPLICATION->IncludeComponent(
                                'bitrix:main.include',
                                '.default',
                                array(
                                    'AREA_FILE_SHOW' => 'file',
                                    'PATH' => $arLogotype['PATH'],
                                    'EDIT_TEMPLATE' => null
                                ),
                                $this->getComponent()
                            ) ?>
                            <?= Html::endTag(!empty($arLogotype['LINK']) ? 'a' : 'div') ?>
                        </div>
                    <?php } ?>
                    <?php if ($sSearchType === 'popup') { ?>
                        <div class="menu-panel-button-wrap intec-grid-item-auto">
                            <div class="menu-panel-search-button">
                                <!--noindex-->
                                <?php $APPLICATION->IncludeComponent(
                                    "bitrix:search.title",
                                    $sSearchTemplate,
                                    $arSearchParams,
                                    $this->getComponent()
                                ) ?>
                                <!--/noindex-->
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <?php if ($sSearchType === 'page') { ?>
                    <div class="menu-panel-search-input">
                        <!--noindex-->
                        <?php $APPLICATION->IncludeComponent(
                            "bitrix:search.title",
                            $sSearchTemplate,
                            $arSearchParams,
                            $this->getComponent()
                        ) ?>
                        <!--/noindex-->
                    </div>
                <?php } ?>
            </div>
            <div class="menu-content" data-role="item" data-current="true">
                <div class="menu-content-wrapper">
                    <div class="menu-items" data-role="items">
                        <?php foreach ($arResult as $arItem) {
                            $fRenderItem($arItem, 0);
                        } ?>

                        <?php if ($arRegionality['USE']) { ?>
                            <?php $APPLICATION->IncludeComponent(
                                'intec.regionality:regions.select',
                                '.default',
                                [],
                                $component
                            ) ?>
                        <?php } ?>

                        <?= Html::beginTag('div', [
                            'class' => [
                                'menu-item' => [
                                    '',
                                    'level-0',
                                    'extra'
                                ],
                                'menu-information'
                            ],
                            'data' => [
                                'role' => 'item',
                                'level' => 0,
                                'expanded' => 'false',
                                'current' => 'false'
                            ]
                        ]) ?>
                            <div class="menu-item-wrapper">
                                <?= Html::beginTag('div', [
                                    'class' => [
                                        'menu-item-content',
                                    ],
                                    'data' => [
                                        'action' => 'menu.item.open'
                                    ]
                                ]) ?>
                                    <div class="menu-information-items">
                                        <?php if ($arAddress['SHOW']) { ?>
                                            <div class="menu-information-item menu-information-address">
                                                <?php if ($arInformationView === 'view.2') { ?>
                                                    <span>
                                                        <?php if ($arCity['SHOW']) { ?>
                                                            <?= $arCity['VALUE'] ?>
                                                        <?php } ?>
                                                        <?= $arAddress['VALUE'] ?>
                                                    </span>
                                                <?php } else { ?>
                                                    <div class="intec-grid intec-grid-nowrap">
                                                        <div class="intec-grid-item-auto">
                                                            <?php if ($arRegionality['USE']) { ?>
                                                                <span class="menu-information-icon"></span>
                                                            <?php } else { ?>
                                                                <i class="menu-information-icon fas fa-map-pin"></i>
                                                            <?php } ?>
                                                        </div>
                                                        <div class="intec-grid-item">
                                                            <?php if ($arCity['SHOW'] && !$arRegionality['USE']) { ?>
                                                                <?= $arCity['VALUE'] ?><br>
                                                            <?php } ?>
                                                            <?= $arAddress['VALUE'] ?>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                        <?php if ($arPhones['SHOW']) { ?>
                                            <?php if ($arInformationView === 'view.2') { ?>
                                                <div class="menu-information-item menu-information-phone-wrap">
                                                    <div data-role="phones" data-state="close">
                                                        <a href="tel:<?= $arPhones['SELECTED']['LINK'] ?>" class="menu-information-phone menu-information-text" data-role="phone.button">
                                                            <i class="menu-information-icon fas fa-phone"></i>
                                                            <?= $arPhones['SELECTED']['DISPLAY'] ?>
                                                            <?php if (!empty($arPhones['VALUES'])) { ?>
                                                                <span class="menu-information-phone-icon">
                                                                    <i class="far fa-angle-right"></i>
                                                                </span>
                                                            <?php } ?>
                                                        </a>
                                                        <?php if (!empty($arPhones['VALUES'])) { ?>
                                                            <div class="menu-information-phones" data-role="phone.items">
                                                                <?php foreach($arPhones['VALUES'] as $arPhone) { ?>
                                                                    <a href="tel:<?= $arPhone['LINK'] ?>" class="menu-information-phone menu-information-text">
                                                                        <?= $arPhone['DISPLAY'] ?>
                                                                    </a>
                                                                <?php } ?>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <?php if ($arEmail['SHOW']) { ?>
                                                    <div class="menu-information-item menu-information-email">
                                                        <a href="mailto:<?= $arEmail['VALUE'] ?>" class="menu-information-text">
                                                            <?= $arEmail['VALUE'] ?>
                                                        </a>
                                                    </div>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <div class="menu-information-item menu-information-phone-wrap">
                                                    <div class="intec-grid intec-grid-nowrap">
                                                        <div class="intec-grid-item-auto">
                                                            <i class="menu-information-icon fas fa-phone"></i>
                                                        </div>
                                                        <div class="intec-grid-item">
                                                            <div data-role="phones" data-state="close">
                                                                <a href="tel:<?= $arPhones['SELECTED']['LINK'] ?>" class="menu-information-phone menu-information-text" data-role="phone.button">
                                                                    <?= $arPhones['SELECTED']['DISPLAY'] ?>
                                                                    <?php if (!empty($arPhones['VALUES'])) { ?>
                                                                        <span class="menu-information-phone-icon">
                                                                            <i class="far fa-angle-right"></i>
                                                                        </span>
                                                                    <?php } ?>
                                                                </a>
                                                                <?php if (!empty($arPhones['VALUES'])) { ?>
                                                                    <div class="menu-information-phones" data-role="phone.items">
                                                                        <?php foreach($arPhones['VALUES'] as $arPhone) { ?>
                                                                            <a href="tel:<?= $arPhone['LINK'] ?>" class="menu-information-phone menu-information-text">
                                                                                <?= $arPhone['DISPLAY'] ?>
                                                                            </a>
                                                                        <?php } ?>
                                                                    </div>
                                                                <?php } ?>
                                                            </div>
                                                            <?php if ($arEmail['SHOW']) { ?>
                                                                <div class="menu-information-email">
                                                                    <a href="mailto:<?= $arEmail['VALUE'] ?>" class="menu-information-text">
                                                                        <?= $arEmail['VALUE'] ?>
                                                                    </a>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                        <?php if ($arAuthorization['SHOW']) { ?>
                                            <?php if ($arInformationView === 'view.2') { ?>
                                                <div class="menu-information-auth">
                                                    <a href="<?= $arAuthorization['VALUE'] ?>" class="menu-information-button-link menu-information-text">
                                                        <i class="menu-information-icon fas fa-lock"></i>
                                                        <?= Loc::getMessage('C_MENU_MOBILE_2_AUTHORIZATION') ?>
                                                    </a>
                                                </div>
                                            <?php } else { ?>
                                                <div class="menu-information-item intec-grid intec-grid-a-v-center">
                                                    <div class="intec-grid intec-grid-a-v-center">
                                                        <div class="intec-grid-item-auto">
                                                            <i class="menu-information-icon fas fa-lock"></i>
                                                        </div>
                                                        <div class="intec-grid-item">
                                                            <a href="<?= $arAuthorization['VALUE'] ?>" class="menu-information-text">
                                                                <?= Loc::getMessage('C_MENU_MOBILE_2_AUTHORIZATION') ?>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        <?php } ?>
                                        <?php
                                            $arBasketTemplate = 'link';
                                            if ($arInformationView === 'view.2')
                                                $arBasketTemplate = 'buttons';
                                        ?>
                                        <? $APPLICATION->IncludeComponent(
                                            "intec.universe:sale.basket.icons",
                                            $arBasketTemplate,
                                            array(
                                                "COMPONENT_TEMPLATE" => ".default",
                                                "BASKET_SHOW" => $arParams['BASKET_SHOW'],
                                                "DELAY_SHOW" => $arParams['DELAY_SHOW'],
                                                "COMPARE_SHOW" => $arParams['COMPARE_SHOW'],
                                                "COMPARE_IBLOCK_TYPE" => $arParams['COMPARE_IBLOCK_TYPE'],
                                                "COMPARE_IBLOCK_ID" => $arParams['COMPARE_IBLOCK_ID'],
                                                "COMPARE_CODE" => $arParams['COMPARE_CODE'],
                                                "BASKET_URL" => $arParams['BASKET_URL'],
                                                "COMPARE_URL" => $arParams['COMPARE_URL']
                                            ),
                                            $component
                                        );?>
                                    </div>
                                    <?php if ($arSocial['SHOW']) { ?>
                                        <div class="menu-social-wrap">
                                            <div class="menu-social intec-grid intec-grid-wrap intec-grid-a-v-center intec-grid-i-10">
                                                <?php foreach ($arSocial['ITEMS'] as $sSocialKey => $arSocialItem) { ?>
                                                    <?php if ($arSocialItem['SHOW']) { ?>
                                                        <div class="menu-social-item intec-grid-item">
                                                            <?= Html::beginTag('a', [
                                                                'href' => $arSocialItem['VALUE'],
                                                                'class' => ''
                                                            ]) ?>
                                                            <?php include('icons/social_'.StringHelper::toLowerCase($sSocialKey).'.svg') ?>
                                                            <?= Html::endTag('a') ?>
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?= Html::endTag('div') ?>
                            </div>
                        <?= Html::endTag('div') ?>
                    </div>
                </div>
            </div>
        <?= Html::endTag('div') ?>
        <script type="text/javascript">
            (function ($, api) {
                $(document).on('ready', function () {
                    var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
                    var menu = $('[data-role="menu"]', root);
                    var page = $('html');
                    var buttons = {};
                    var state = false;

                    menu.items = $('[data-role="item"]', root);
                    buttons.open = $('[data-action="menu.open"]', root);
                    buttons.close = $('[data-action="menu.close"]', root);

                    menu.open = function () {
                        if (state) return;

                        state = true;
                        menu.css({
                            'display': 'block'
                        }).stop().animate({
                            'opacity': 1
                        }, 500);

                        page.css({
                            'overflow': 'hidden',
                            'height': '100%'
                        });
                    };

                    menu.close = function () {
                        if (!state) return;

                        state = false;
                        menu.stop().animate({
                            'opacity': 0
                        }, 500, function () {
                            menu.css({
                                'display': 'none'
                            });

                            page.css({
                                'overflow': '',
                                'height': ''
                            });
                        });
                    };

                    buttons.open.on('click', menu.open);
                    buttons.close.on('click', menu.close);

                    menu.items.each(function () {
                        var item = $(this);
                        var parent = item.parents('[data-role="item"]').first();
                        var items = $('[data-role="items"]', item).first();
                        var buttons = {};
                        var state = false;

                        parent.items = $('[data-role="items"]', parent).first();

                        if (items.size() === 0)
                            return;

                        buttons.open = $('[data-action="menu.item.open"]', item).first();
                        buttons.close = $('[data-action="menu.item.close"]', items).first();

                        item.open = function () {
                            if (state) return;

                            state = true;
                            menu.items.attr('data-current', 'false');
                            item.attr('data-expanded', 'true');
                            item.attr('data-current', 'true');
                            parent.items.scrollTop(0);
                        };

                        item.close = function () {
                            if (!state) return;

                            state = false;
                            menu.items.attr('data-current', 'false');
                            item.attr('data-expanded', 'false');
                            parent.attr('data-current', 'true');
                        };

                        buttons.open.on('click', item.open);
                        buttons.close.on('click', item.close);
                    });

                    var phone = $('[data-role="phones"]', root);
                    var phoneButton = $('[data-role="phone.button"]', phone);
                    var phoneItems = $('[data-role="phone.items"]', phone);

                    phoneButton.on('click', function(){
                        var phoneState = phone.attr('data-state');

                        if (phoneState == 'open') {
                            phone.attr('data-state', 'close');
                        } else if (phoneState == 'close') {
                            phone.attr('data-state', 'open');
                        }

                        phoneItems.slideToggle();
                    })
                })
            })(jQuery, intec);
        </script>
    </div>
<?php } ?>