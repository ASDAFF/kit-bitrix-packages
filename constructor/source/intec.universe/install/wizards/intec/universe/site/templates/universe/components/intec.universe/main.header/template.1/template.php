<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use intec\core\bitrix\Component;
use intec\core\bitrix\component\InnerTemplate;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

/** @var InnerTemplate[] $arTemplates */
$arTemplates = $arResult['TEMPLATES'];
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));
$arData = [
    'id' => $sTemplateId
];

$arVisual = $arResult['VISUAL'];

$this->setFrameMode(true);

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => Html::cssClassFromArray([
        'widget' => [
            '' => true,
            'transparent' => $arVisual['TRANSPARENCY']
        ],
        'c-header' => [
            '' => true,
            'template-1' => true
        ]
    ], true),
    'data' => [
        'transparent' => $arVisual['TRANSPARENCY'] ? 'true' : 'false'
    ]
]) ?>
    <div class="widget-content">
        <?php if (!empty($arTemplates['DESKTOP'])) { ?>
            <div class="widget-view widget-view-desktop">
                <?php $arData['type'] = 'DESKTOP' ?>
                <?php $arData['selector'] = '.widget-view.widget-view-desktop' ?>
                <?= $arTemplates['DESKTOP']->render(
                    $arParams,
                    $arResult,
                    $arData
                ) ?>
            </div>
        <?php } ?>
        <?php if (!empty($arTemplates['FIXED'])) { ?>
            <div class="widget-view widget-view-fixed">
                <?php $arData['type'] = 'FIXED' ?>
                <?php $arData['selector'] = '.widget-view.widget-view-fixed' ?>
                <?= $arTemplates['FIXED']->render(
                    $arParams,
                    $arResult,
                    $arData
                ) ?>
            </div>
            <script type="text/javascript">
                (function ($, api) {
                    $(document).on('ready', function () {
                        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
                        var state = false;
                        var area = $(window);
                        var views;
                        var update;

                        update = function () {
                            var bound = 0;

                            if (views.desktop.is(':visible')) {
                                bound += views.desktop.height();
                                bound += views.desktop.offset().top;
                            }

                            if (area.scrollTop() > bound) {
                                views.fixed.show();
                            } else {
                                views.fixed.hide();
                            }
                        };

                        views = {};
                        views.desktop = $('.widget-view.widget-view-desktop', root);
                        views.fixed = $('.widget-view.widget-view-fixed', root);
                        views.fixed.css({
                            'top': -views.fixed.height()
                        });

                        views.fixed.show = function () {
                            var view = views.fixed;

                            if (state) return;

                            state = true;
                            view.css({
                                'display': 'block'
                            });

                            view.trigger('show');
                            view.stop().animate({
                                'top': 0
                            }, 500)
                        };

                        views.fixed.hide = function () {
                            var view = views.fixed;

                            if (!state) return;

                            state = false;
                            view.stop().animate({
                                'top': -view.height()
                            }, 500, function () {
                                view.trigger('hide');
                                view.css({
                                    'display': 'none'
                                })
                            })
                        };

                        update();

                        area.on('scroll', update)
                            .on('resize', update);
                    });
                })(jQuery, intec);
            </script>
        <?php } ?>
        <?php if (!empty($arTemplates['MOBILE'])) { ?>
            <?= Html::beginTag('div', [
                'class' => Html::cssClassFromArray([
                    'widget-view' => true,
                    'widget-view-mobile' => true
                ], true)
            ]) ?>
                <?php $arData['type'] = 'MOBILE' ?>
                <?php $arData['selector'] = '.widget-view.widget-view-mobile' ?>
                <?= $arTemplates['MOBILE']->render(
                    $arParams,
                    $arResult,
                    $arData
                ) ?>
            <?= Html::endTag('div') ?>
            <?php if ($arResult['MOBILE']['FIXED']) { ?>
                <script type="text/javascript">
                    (function ($, api) {
                        $(document).on('ready', function () {
                            var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
                            var area = $(window);
                            var view;
                            var update;
                            var stub = null;
                            var scrollPrev = 0;

                            view = $('.widget-view.widget-view-mobile', root);
                            update = function () {
                                var bound = 0;

                                view.removeClass('widget-view-mobile-fixed');

                                if (view.is(':visible')) {
                                    bound += view.offset().top;

                                    if (area.scrollTop() > bound) {
                                        if (stub === null) {
                                            stub = $('<div></div>');
                                            view.after(stub);
                                        }

                                        stub.css({
                                            'height': view.height()
                                        });

                                        view.addClass('widget-view-mobile-fixed');
                                    } else {
                                        if (stub !== null) {
                                            stub.remove();
                                            stub = null;
                                        }
                                    }
                                } else {
                                    if (stub !== null) {
                                        stub.remove();
                                        stub = null;
                                    }
                                }
                            };

                            update();

                            area.on('scroll', function () {
                                    update();

                                    <?php if ($arResult['MOBILE']['HIDDEN']) { ?>
                                        var scrolled = area.scrollTop();

                                        if ( scrolled > 100 && scrolled > scrollPrev ) {
                                                view.addClass('widget-view-mobile-out');
                                            } else {
                                                view.removeClass('widget-view-mobile-out');
                                            }
                                            scrollPrev = scrolled;
                                    <?php } ?>
                                })
                                .on('resize', update);
                        });
                    })(jQuery, intec);
                </script>
            <?php } ?>
        <?php } ?>
        <?php if (!empty($arTemplates['BANNER'])) { ?>
            <div class="widget-banner">
                <?php $arData['type'] = 'BANNER' ?>
                <?= $arTemplates['BANNER']->render(
                    $arParams,
                    $arResult,
                    $arData
                ) ?>
            </div>
        <?php } ?>
    </div>
<?= Html::endTag('div') ?>