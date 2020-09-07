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
    'PHONE_SHOW' => 'N',
    'PHONE' => null,
    'EMAIL_SHOW' => 'N',
    'EMAIL' => null,
    'LOGOTYPE_SHOW' => 'N',
    'LOGOTYPE' => null,
    'LOGOTYPE_LINK' => null,
    'REGIONALITY_USE' => 'N'
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

$arPhone = [
    'SHOW' => $arParams['PHONE_SHOW'] === 'Y',
    'VALUE' => $arParams['PHONE']
];

if (empty($arPhone['VALUE'])) {
    $arPhone['SHOW'] = false;
} else {
    $arPhone['VALUE'] = [
        'DISPLAY' => $arPhone['VALUE'],
        'LINK' => StringHelper::replace($arPhone['VALUE'], [
            '(' => '',
            ')' => '',
            ' ' => '',
            '-' => ''
        ])
    ];
}

$arEmail = [
    'SHOW' => $arParams['EMAIL_SHOW'] === 'Y',
    'VALUE' => $arParams['EMAIL']
];

if (empty($arEmail['VALUE']))
    $arEmail['SHOW'] = false;

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
                                            <?= Loc::getMessage('C_MENU_MOBILE_1_BACK') ?>
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
    <div id="<?= $sTemplateId ?>" class="ns-bitrix c-menu c-menu-mobile-1">
        <div class="menu-button intec-cl-text-hover" data-action="menu.open">
            <i class="menu-button-icon glyph-icon-menu-icon"></i>
        </div>
        <div class="menu" data-role="menu">
            <div class="menu-panel">
                <div class="menu-panel-wrapper intec-grid intec-grid-nowrap intec-grid-i-h-10 intec-grid-a-v-center">
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
                    <div class="menu-panel-button-wrap intec-grid-item-auto">
                        <div class="menu-panel-button intec-cl-text-hover" data-action="menu.close">
                            <i class="glyph-icon-cancel"></i>
                        </div>
                    </div>
                </div>
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
                        <?php if ($arAddress['SHOW']) { ?>
                            <?= Html::beginTag('div', [
                                'class' => [
                                    'menu-item' => [
                                        '',
                                        'level-0',
                                        'extra'
                                    ]
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
                                            'intec-cl' => [
                                                'text-hover'
                                            ]
                                        ],
                                        'data' => [
                                            'action' => 'menu.item.open'
                                        ]
                                    ]) ?>
                                        <div class="intec-grid intec-grid-nowrap intec-grid-i-h-10 intec-grid-a-v-center">
                                            <div class="menu-item-icon-wrap intec-grid-item-auto">
                                                <div class="menu-item-icon intec-cl-text">
                                                    <i class="fas fa-map-marked-alt"></i>
                                                </div>
                                            </div>
                                            <div class="menu-item-text-wrap intec-grid-item intec-grid-item-shrink-1">
                                                <div class="menu-item-text">
                                                    <?= $arAddress['VALUE'] ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?= Html::endTag('div') ?>
                                </div>
                            <?= Html::endTag('div') ?>
                        <?php } ?>
                        <?php if ($arPhone['SHOW']) { ?>
                            <?= Html::beginTag('div', [
                                'class' => [
                                    'menu-item' => [
                                        '',
                                        'level-0',
                                        'extra'
                                    ]
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
                                            'intec-cl' => [
                                                'text-hover'
                                            ]
                                        ],
                                        'data' => [
                                            'action' => 'menu.item.open'
                                        ]
                                    ]) ?>
                                        <div class="intec-grid intec-grid-nowrap intec-grid-i-h-10 intec-grid-a-v-center">
                                            <div class="menu-item-icon-wrap intec-grid-item-auto">
                                                <div class="menu-item-icon intec-cl-text">
                                                    <i class="fas fa-phone"></i>
                                                </div>
                                            </div>
                                            <div class="menu-item-text-wrap intec-grid-item intec-grid-item-shrink-1">
                                                <a href="tel:<?= $arPhone['VALUE']['LINK'] ?>" class="menu-item-text">
                                                    <?= $arPhone['VALUE']['DISPLAY'] ?>
                                                </a>
                                            </div>
                                        </div>
                                    <?= Html::endTag('div') ?>
                                </div>
                            <?= Html::endTag('div') ?>
                        <?php } ?>
                        <?php if ($arEmail['SHOW']) { ?>
                            <?= Html::beginTag('div', [
                                'class' => [
                                    'menu-item' => [
                                        '',
                                        'level-0',
                                        'extra'
                                    ]
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
                                            'intec-cl' => [
                                                'text-hover'
                                            ]
                                        ],
                                        'data' => [
                                            'action' => 'menu.item.open'
                                        ]
                                    ]) ?>
                                        <div class="intec-grid intec-grid-nowrap intec-grid-i-h-10 intec-grid-a-v-center">
                                            <div class="menu-item-icon-wrap intec-grid-item-auto">
                                                <div class="menu-item-icon intec-cl-text">
                                                    <i class="fas fa-envelope"></i>
                                                </div>
                                            </div>
                                            <div class="menu-item-text-wrap intec-grid-item intec-grid-item-shrink-1">
                                                <a href="mailto:<?= $arEmail['VALUE'] ?>" class="menu-item-text">
                                                    <?= $arEmail['VALUE'] ?>
                                                </a>
                                            </div>
                                        </div>
                                    <?= Html::endTag('div') ?>
                                </div>
                            <?= Html::endTag('div') ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
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
                })
            })(jQuery, intec);
        </script>
    </div>
<?php } ?>