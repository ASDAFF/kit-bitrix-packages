<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\StringHelper;

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

$arButtons = [
    'CLOSE' => [
        'POSITION' => ArrayHelper::fromRange(['left', 'right'], $arParams['BUTTONS_CLOSE_POSITION'])
    ]
];

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
        'c-menu-popup-1'
    ]
]) ?>
    <div class="menu-button intec-cl-text-hover" data-action="menu.open">
        <i class="menu-button-icon fal fa-bars"></i>
    </div>
    <div class="menu" data-role="menu">
        <div class="menu-wrapper intec-content intec-content-primary">
            <div class="menu-wrapper-2 intec-content-wrapper">
                <div class="menu-panel">
                    <div class="menu-panel-wrapper intec-grid intec-grid-nowrap intec-grid-i-h-10 intec-grid-a-v-center">
                        <div class="menu-panel-button-wrap intec-grid-item-auto">
                            <?php if ($arButtons['CLOSE']['POSITION'] == 'left') { ?>
                                <div class="menu-panel-button intec-cl-text-hover" data-action="menu.close">
                                    <i class="glyph-icon-cancel"></i>
                                </div>
                            <?php } ?>
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
                        <?php } else { ?>
                            <div class="intec-grid-item"></div>
                        <?php } ?>
                            <div class="menu-panel-button-wrap intec-grid-item-auto">
                                <?php if ($arButtons['CLOSE']['POSITION'] == 'right') { ?>
                                    <div class="menu-panel-button intec-cl-text-hover" data-action="menu.close">
                                        <i class="glyph-icon-cancel"></i>
                                    </div>
                                <?php } ?>
                            </div>
                    </div>
                </div>
                <div class="menu-content">
                    <div class="menu-content-wrapper" >
                        <div class="menu-content-wrapper-2 scroll-mod-hiding scrollbar-inner" data-role="scroll">
                            <div class="menu-content-wrapper-3">
                                <?php $fRender($arResult, 0) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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