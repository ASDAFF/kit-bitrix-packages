<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Type;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\StringHelper;
use Bitrix\Main\Localization\Loc;

/** @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arMacros = [
    'SITE_DIR' => SITE_DIR,
    'SITE_TEMPLATE_PATH' => SITE_TEMPLATE_PATH.'/'
];

$arLogotype = [
    'SHOW' => ArrayHelper::getValue($arParams, 'LOGOTYPE_SHOW') == 'Y',
    'PATH' => ArrayHelper::getValue($arParams, 'LOGOTYPE', null),
    'LINK' => ArrayHelper::getValue($arParams, 'LOGOTYPE_LINK', null),
];

$arLogotype['PATH'] = trim($arLogotype['PATH']);
$arLogotype['PATH'] = StringHelper::replaceMacros($arLogotype['PATH'], $arMacros);
$arLogotype['SHOW'] = $arLogotype['SHOW'] && !empty($arLogotype['PATH']);

$arVisual = $arResult['VISUAL'];
$arContacts = $arResult['CONTACTS'];
$iContactsCount = count($arContacts);
$arSocial = $arResult['SOCIAL'];
$arForms = $arResult['FORMS'];

include(__DIR__.'/parts/views.php');

/**
 * @param array $arItems
 * @param integer $iLevel
 * @param array $arParent
 */

$fRender = function ($arItems, $iLevel, $arParent = null) use (&$fRender, &$arViews) {
    $sView = 'simple.level.'.$iLevel;

    if (empty($arViews[$sView]))
        $sView = 'simple.level.*';

    if (empty($arViews[$sView]))
        return;

    $fView = $arViews[$sView];
    $fView($arItems, $iLevel, $arParent);
}
?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-bitrix',
        'c-menu',
        'c-menu-popup-3'
    ],
    'data-theme' => $arVisual['THEME']
]) ?>
    <div class="menu-button intec-cl-text-hover" data-action="menu.open">
        <i class="menu-button-icon glyph-icon-menu-icon"></i>
    </div>
    <?= Html::beginTag('div', [
        'class' => Html::cssClassFromArray([
            'menu' => true,
            'background-image' => $arVisual['BACKGROUND']['TYPE'] == 'picture' && !empty($arVisual['BACKGROUND']['URL'])
        ], true),
        'data-role' => 'menu',
        'style' => Html::cssStyleFromArray([
            'background-image' => $arVisual['BACKGROUND']['TYPE'] == 'picture' && !empty($arVisual['BACKGROUND']['URL']) ? 'url(' . $arVisual['BACKGROUND']['URL'] . ')' : null,
            'background-color' => $arVisual['BACKGROUND']['TYPE'] == 'color' && !empty($arVisual['BACKGROUND']['COLOR']) ? $arVisual['BACKGROUND']['COLOR'] : null
        ])
    ]) ?>
        <div class="menu-wrapper intec-content intec-content-primary">
            <div class="menu-wrapper-2 intec-content-wrapper">
                <div class="menu-panel">
                    <div class="menu-panel-wrapper intec-grid intec-grid-nowrap intec-grid-i-h-10 intec-grid-a-v-center">
                        <?php if ($arLogotype['SHOW']) { ?>
                            <div class="menu-panel-logotype-wrap intec-grid-item-auto">
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

                        <div class="intec-grid-item"></div>

                        <div class="intec-grid-item-4">
                            <div class="intec-grid intec-grid-a-h-between intec-grid-a-v-center">
                                <?php if ($arContacts['SHOW'] && !empty($arContacts['PHONE'])) { ?>
                                    <div class="menu-panel-contact-wrap intec-grid-item-auto">
                                        <a class="menu-panel-contact" href="tel:<?= $arContacts['PHONE']['VALUE'] ?>">
                                            <?= Html::tag('i', '', [
                                                'class' => Html::cssClassFromArray([
                                                    'icon-phone' => true,
                                                    'glyph-icon-phone' => true,
                                                    'intec-cl-text' => $arVisual['THEME'] == 'light' ? true : false
                                                ], true)
                                            ]) ?>
                                            <span><?= $arContacts['PHONE']['DISPLAY'] ?></span>
                                        </a>
                                        <?php if ($arForms['CALL']['SHOW']) { ?>
                                            <div class="menu-panel-call-wrap">
                                                <?= Html::tag('span', $arForms['CALL']['TITLE'], [
                                                    'class' => Html::cssClassFromArray([
                                                        'menu-panel-call' => true,
                                                        'intec-cl-text-hover' => $arVisual['THEME'] == 'light' ? true : false,
                                                        'intec-cl-border-hover' => $arVisual['THEME'] == 'light' ? true : false,
                                                    ], true),
                                                    'data' => [
                                                        'role' => 'forms.button',
                                                        'action' => 'call.open',
                                                    ]
                                                ]) ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                <?php } else { ?>
                                    <div class="intec-grid-item"></div>
                                <?php } ?>
                                <div class="menu-panel-button-wrap intec-grid-item-auto">
                                    <div class="menu-panel-button intec-cl-text-hover" data-action="menu.close">
                                        <i class="glyph-icon-cancel"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="menu-content">
                    <div class="menu-content-wrapper intec-grid intec-grid-nowrap intec-grid-a-v-stretch">
                        <div class="menu-content-items intec-grid-item">
                            <?php if ($arVisual['SEARCH']['SHOW']) { ?>
                            <div class="menu-content-search-wrap">
                                <div class="menu-content-search">
                                    <?php include(__DIR__.'/parts/search/input.1.php') ?>
                                </div>
                            </div>
                            <?php } ?>
                            <div class="menu-content-items-wrapper scroll-mod-hiding scrollbar-inner" data-role="scroll">
                                <div class="menu-content-items-wrapper-2">
                                    <?php if (!empty($arResult['MENU']['CATALOGS'])) { ?>
                                        <div class="menu-content-catalog">
                                            <?php foreach ($arResult['MENU']['CATALOGS'] as $arCatalog) { ?>
                                                <div class="menu-content-catalog-title-wrap">
                                                    <span class="menu-content-catalog-icon">
                                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M3.16667 10.6667C2.24583 10.6667 1.5 9.92084 1.5 9.00001C1.5 8.07918 2.24583 7.33334 3.16667 7.33334C4.0875 7.33334 4.83333 8.07918 4.83333 9.00001C4.83333 9.92084 4.0875 10.6667 3.16667 10.6667Z" stroke="#B0B0B0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M8.99992 10.6667C8.07909 10.6667 7.33325 9.92084 7.33325 9.00001C7.33325 8.07918 8.07909 7.33334 8.99992 7.33334C9.92075 7.33334 10.6666 8.07918 10.6666 9.00001C10.6666 9.92084 9.92075 10.6667 8.99992 10.6667Z" stroke="#B0B0B0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M14.8334 10.6667C13.9126 10.6667 13.1667 9.92084 13.1667 9.00001C13.1667 8.07918 13.9126 7.33334 14.8334 7.33334C15.7542 7.33334 16.5001 8.07918 16.5001 9.00001C16.5001 9.92084 15.7542 10.6667 14.8334 10.6667Z" stroke="#B0B0B0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M3.16667 16.5C2.24583 16.5 1.5 15.7542 1.5 14.8333C1.5 13.9125 2.24583 13.1667 3.16667 13.1667C4.0875 13.1667 4.83333 13.9125 4.83333 14.8333C4.83333 15.7542 4.0875 16.5 3.16667 16.5Z" stroke="#B0B0B0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M8.99992 16.5C8.07909 16.5 7.33325 15.7542 7.33325 14.8333C7.33325 13.9125 8.07909 13.1667 8.99992 13.1667C9.92075 13.1667 10.6666 13.9125 10.6666 14.8333C10.6666 15.7542 9.92075 16.5 8.99992 16.5Z" stroke="#B0B0B0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M14.8334 16.5C13.9126 16.5 13.1667 15.7542 13.1667 14.8333C13.1667 13.9125 13.9126 13.1667 14.8334 13.1667C15.7542 13.1667 16.5001 13.9125 16.5001 14.8333C16.5001 15.7542 15.7542 16.5 14.8334 16.5Z" stroke="#B0B0B0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M3.16667 4.83333C2.24583 4.83333 1.5 4.0875 1.5 3.16667C1.5 2.24583 2.24583 1.5 3.16667 1.5C4.0875 1.5 4.83333 2.24583 4.83333 3.16667C4.83333 4.0875 4.0875 4.83333 3.16667 4.83333Z" stroke="#B0B0B0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M8.99992 4.83333C8.07909 4.83333 7.33325 4.0875 7.33325 3.16667C7.33325 2.24583 8.07909 1.5 8.99992 1.5C9.92075 1.5 10.6666 2.24583 10.6666 3.16667C10.6666 4.0875 9.92075 4.83333 8.99992 4.83333Z" stroke="#B0B0B0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                            <path d="M14.8334 4.83333C13.9126 4.83333 13.1667 4.0875 13.1667 3.16667C13.1667 2.24583 13.9126 1.5 14.8334 1.5C15.7542 1.5 16.5001 2.24583 16.5001 3.16667C16.5001 4.0875 15.7542 4.83333 14.8334 4.83333Z" stroke="#B0B0B0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                    </span>
                                                    <?= Html::beginTag('a', [
                                                        'class' => Html::cssClassFromArray([
                                                            'menu-content-catalog-title' => true,
                                                            'intec-cl-text-hover' => $arVisual['THEME'] == 'light' ? true : false
                                                        ], true),
                                                        'href' => $arCatalog['LINK'],
                                                        'rel' => 'nofollow'
                                                    ]) ?>
                                                        <?= $arCatalog['TEXT'] ?>
                                                    <?= Html::endTag('a') ?>
                                                </div>
                                                <div class="menu-content-catalog-items">
                                                    <?php $fRender($arCatalog['ITEMS'], 0) ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                    <div class="menu-content-other-items">
                                        <?php $fRender($arResult['MENU']['OTHER'], 0) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if ($arContacts['SHOW']
                            || $arSocial['SHOW']
                            || $arForms['FEEDBACK']['SHOW']
                            || $arVisual['AUTHORIZATION']['SHOW']
                            || $arVisual['BASKET']['SHOW']
                            || $arVisual['DELAY']['SHOW']
                            || $arVisual['COMPARE']['SHOW']
                        ) { ?>
                            <div class="menu-content-contacts-wrap intec-grid-item-4">
                                <?php if ($arForms['FEEDBACK']['SHOW']) { ?>
                                    <div class="menu-content-feedback-wrap">
                                        <?= Html::tag('span', $arForms['FEEDBACK']['TITLE'], [
                                            'class' => Html::cssClassFromArray([
                                                'intec-ui' => [
                                                    '' => true,
                                                    'control-button' => true,
                                                    'mod-round-4' => true,
                                                    'mod-transparent' => true,
                                                    'scheme-current' => $arVisual['THEME'] == 'light' ? true : false
                                                ],
                                                'menu-content-feedback' => true,
                                                'intec-cl-text-hover' => $arVisual['THEME'] == 'dark' ? true : false
                                            ], true),
                                            'data' => [
                                                'role' => 'forms.button',
                                                'action' => 'feedback.open'
                                            ]
                                        ]) ?>
                                    </div>
                                <?php } ?>

                                <?php if ($arVisual['AUTHORIZATION']['SHOW']) { ?>
                                    <?php include(__DIR__.'/parts/auth/panel.php') ?>
                                <?php } ?>

                                <?php if ($arVisual['BASKET']['SHOW'] || $arVisual['DELAY']['SHOW'] || $arVisual['COMPARE']['SHOW']) { ?>
                                    <?php include(__DIR__.'/parts/basket.php') ?>
                                <?php } ?>

                                <?php if ($arContacts['SHOW']) { ?>
                                    <div class="menu-content-contacts">
                                        <div class="menu-content-contacts-items">
                                            <?php if (!empty($arContacts['CITY'])) { ?>
                                                <div class="menu-content-contacts-name intec-grid intec-grid-a-v-center intec-grid-i-h-4">
                                                    <span class="menu-content-contacts-icon intec-grid-item-auto">
                                                        <i class="fal fa-map-marker-alt"></i>
                                                    </span>
                                                    <span class="intec-grid-item">
                                                        <?= $arContacts['CITY'] ?>
                                                    </span>
                                                </div>
                                            <?php } ?>
                                            <?php if (!empty($arContacts['ADDRESS'])) { ?>
                                                <div class="menu-content-contacts-address intec-grid intec-grid-a-v-center intec-grid-i-h-4">
                                                    <span class="menu-content-contacts-icon intec-grid-item-auto">
                                                        <i class="fal fa-map"></i>
                                                    </span>
                                                    <span class="intec-grid-item">
                                                        <?= $arContacts['ADDRESS'] ?>
                                                    </span>
                                                </div>
                                            <?php } ?>
                                            <?php if (!empty($arContacts['EMAIL'])) { ?>
                                                <?= Html::beginTag('a', [
                                                    'class' => Html::cssClassFromArray([
                                                        'menu-content-contacts-mail' => true,
                                                        'intec-grid' => true,
                                                        'intec-grid-a-v-center' => true,
                                                        'intec-grid-i-h-4' => true,
                                                        'intec-cl' => [
                                                            'text-hover' => $arVisual['THEME'] == 'light' ? true : false,
                                                        ]
                                                    ], true),
                                                    'href' => 'mailto:'.$arContacts['EMAIL']
                                                ]) ?>
                                                    <span class="menu-content-contacts-icon intec-grid-item-auto">
                                                        <i class="fal fa-envelope"></i>
                                                    </span>
                                                    <span class="intec-grid-item">
                                                        <?= $arContacts['EMAIL'] ?>
                                                    </span>
                                                <?= Html::endTag('a') ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php if ($arSocial['SHOW']) { ?>
                                    <div class="menu-content-social">
                                        <div class="menu-content-social-icons intec-grid intec-grid-nowrap intec-grid-i-h-6">
                                            <?php foreach ($arSocial['ITEMS'] as $arItem) { ?>
                                                <?php if (empty($arItem['LINK'])) continue ?>
                                                <div class="menu-content-social-icon-wrap intec-grid-item-auto">
                                                    <?= Html::beginTag('a', [
                                                        'class' => Html::cssClassFromArray([
                                                            'menu-content-social-icon' => true,
                                                            'intec-cl' => [
                                                                'border-hover' => $arVisual['THEME'] == 'light' ? true : false,
                                                                'background-hover' => $arVisual['THEME'] == 'light' ? true : false,
                                                            ]
                                                        ], true),
                                                        'href' => $arItem['LINK']
                                                    ]) ?>
                                                        <i class="<?= $arItem['ICON'] ?>"></i>
                                                    <?= Html::endTag('a') ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <?php include(__DIR__.'/parts/form.php') ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?= Html::endTag('div') ?>

    <script type="text/javascript">
        (function ($, api) {
            $(document).on('ready', function () {
                var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
                var menu = $('[data-role="menu"]', root);
                var buttons = {};
                var state = false;
                var scroll = $('[data-role="scroll"]', root);
                var scrollValue = 0;

                menu.items = $('[data-role="menu.item"]', menu);
                buttons.open = $('[data-action="menu.open"]', root);
                buttons.close = $('[data-action="menu.close"]', root);

                scroll.scrollbar();

                menu.open = function () {
                    if (state) return;

                    state = true;
                    scrollValue = $(window).scrollTop();
                    menu.css({
                        'display': 'block'
                    }).stop().animate({
                        'opacity': 1
                    }, 500);
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
                    });
                };

                $(window).on('scroll', function () {
                    if (state && $(window).scrollTop() !== scrollValue)
                        $(window).scrollTop(scrollValue);
                });

                buttons.open.on('click', menu.open);
                buttons.close.on('click', menu.close);

                menu.items.each(function () {
                    var item = $(this);
                    var items = $('[data-role="menu.items"]', item).first();
                    var buttons = {};
                    var state = false;
                    var expanded = item.data('expanded');

                    buttons.toggle = $('[data-action="menu.item.toggle"]', item)
                        .not(items.find('[data-action="menu.item.toggle"]'))
                        .first();

                    item.open = function () {
                        var height = {};

                        if (state) return;

                        state = true;
                        item.attr('data-expanded', 'true');

                        height.old = items.height();
                        items.css({
                            'height': 'auto'
                        });

                        height.new = items.height();
                        items.css({
                            'height': height.old
                        });

                        items.stop().animate({
                            'height': height.new
                        }, 350, function () {
                            items.css({
                                'height': 'auto'
                            })
                        })
                    };

                    item.close = function () {
                        if (!state) return;

                        state = false;
                        item.attr('data-expanded', 'false');

                        items.stop().animate({
                            'height': 0
                        }, 350);
                    };

                    if (expanded)
                        item.open();

                    buttons.toggle.on('click', function () {
                        if (state) {
                            item.close();
                        } else {
                            item.open();
                        }
                    });
                });
            })
        })(jQuery, intec);
    </script>
<?= Html::endTag('div') ?>