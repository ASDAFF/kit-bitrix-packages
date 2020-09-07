<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arVisual
 * @var string $sTemplateId
 * @var string $sTemplateContainer
 * @var array $arVisual
 * @var array $arNavigation
 */

?>
<script type="text/javascript">
    (function ($, api) {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);

        $(function () {
            var properties = root.data('properties');
            var items;
            var component;
            var order;

            <?php if ($arResult['FORM']['SHOW']) { ?>
                order = function (data) {
                    var options = <?= JavaScript::toObject([
                        'id' => $arResult['FORM']['ID'],
                        'template' => $arResult['FORM']['TEMPLATE'],
                        'parameters' => [
                            'AJAX_OPTION_ADDITIONAL' => $sTemplateId.'-form',
                            'CONSENT_URL' => $arResult['URL']['CONSENT']
                        ],
                        'settings' => [
                            'title' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_LIST_1_FORM_TITLE')
                        ]
                    ]) ?>;

                    options.fields = {};

                    <?php if (!empty($arResult['FORM']['PROPERTIES']['PRODUCT'])) { ?>
                        options.fields[<?= JavaScript::toObject($arResult['FORM']['PROPERTIES']['PRODUCT']) ?>] = data.name;
                    <?php } ?>

                    universe.forms.show(options);

                    if (window.yandex && window.yandex.metrika) {
                        window.yandex.metrika.reachGoal('forms.open');
                        window.yandex.metrika.reachGoal(<?= JavaScript::toObject('forms.'.$arResult['FORM']['ID'].'.open') ?>);
                    }
                };
            <?php } ?>

            root.update = function () {
                var handled = [];

                if (api.isDeclared(items))
                    handled = items.handled;

                items = $('[data-role="item"]', root);
                items.handled = handled;
                items.each(function () {
                    var item = $(this);
                    var data = item.data('data');
                    var entity = data;
                    var expanded = false;

                    if (handled.indexOf(this) > -1)
                        return;

                    handled.push(this);
                    item.offers = new universe.catalog.offers({
                        'properties': properties.length !== 0 ? properties : data.properties,
                        'list': data.offers
                    });

                    item.article = $('[data-role="article"]', item);
                    item.article.value = $('[data-role="article.value"]', item.article);
                    item.stores = $('[data-role="stores.popup.window"]', item);
                    item.stores.controls = $('[data-role="stores.popup.button"]', item);
                    item.stores.controls.toggle = item.stores.controls.filter('[data-popup="toggle"]');
                    item.stores.controls.close = item.stores.controls.filter('[data-popup="close"]');
                    item.gallery = $('[data-role="gallery"]', item);
                    item.counter = $('[data-role="item.counter"]', item);
                    item.price = $('[data-role="item.price"]', item);
                    item.price.base = $('[data-role="item.price.base"]', item.price);
                    item.price.discount = $('[data-role="item.price.discount"]', item.price);
                    item.price.extended = $('[data-role="price.extended.popup.window"]', item.price);
                    item.price.extended.toggle = $('[data-role="price.extended.popup.toggle"]', item.price);
                    item.price.extended.close = $('[data-role="price.extended.popup.close"]', item.price);
                    item.order = $('[data-role="item.order"]', item);
                    item.quantity = api.controls.numeric({
                        'bounds': {
                            'minimum': entity.quantity.ratio,
                            'maximum': entity.quantity.trace && !entity.quantity.zero ? entity.quantity.value : false
                        },
                        'step': entity.quantity.ratio
                    }, item.counter);

                    <?php if ($arResult['QUICK_VIEW']['USE']) { ?>
                        item.quickView = $('[data-role="quick.view"]', item);

                        item.quickView.on('click', function () {
                            universe.components.show({
                                'component': 'bitrix:catalog.element',
                                'template': data.quickView.template,
                                'parameters': data.quickView.parameters,
                                'settings': {
                                    'parameters': {
                                        'className': 'popup-window-quick-view',
                                        'width': null
                                    }
                                }
                            });
                        });
                    <?php } ?>

                    item.update = function () {
                        var article = entity.article;
                        var price = null;

                        item.attr('data-available', entity.available ? 'true' : 'false');

                        if (article === null)
                            article = data.article;

                        item.article.attr('data-show', article === null ? 'false' : 'true');
                        item.article.value.text(article);

                        api.each(entity.prices, function (index, object) {
                            if (object.quantity.from === null || item.quantity.get() >= object.quantity.from)
                                price = object;
                        });

                        if (price !== null) {
                            item.price.attr('data-discount', price.discount.use ? 'true' : 'false');
                            item.price.base.html(price.base.display);
                            item.price.discount.html(price.discount.display);
                        } else {
                            item.price.attr('data-discount', 'false');
                            item.price.base.html(null);
                            item.price.discount.html(null);
                        }

                        item.price.attr('data-show', price !== null ? 'true' : 'false');
                        item.quantity.configure({
                            'bounds': {
                                'minimum': entity.quantity.ratio,
                                'maximum': entity.quantity.trace && !entity.quantity.zero ? entity.quantity.value : false
                            },
                            'step': entity.quantity.ratio
                        });

                        item.find('[data-offer]').css('display', '');

                        if (entity !== data) {
                            item.find('[data-offer=' + entity.id + ']').css('display', 'block');
                            item.find('[data-offer="false"]').css('display', 'none');

                            if (item.gallery.filter('[data-offer=' + entity.id + ']').length === 0)
                                item.gallery.filter('[data-offer="false"]').css('display', 'block');

                            $.each(entity.values, function (key, value) {
                                var property = item.find('[data-property=' + key + ']');
                                var selectedValue = property.find('[data-value=' + value + ']');
                                var selectedValueContainer = property.find('[data-role="item.property.value.selected"]');

                                var valueName = selectedValue.find('[data-role="item.property.value.name"]').html();

                                selectedValueContainer.html(valueName);
                            });

                            item.find('[data-role="item.property.value"]')
                                .removeClass('intec-cl-background intec-cl-border');
                            item.find('[data-role="item.property.value"][data-state="selected"]')
                                .addClass('intec-cl-background intec-cl-border');

                        }

                        item.find('[data-basket-id]')
                            .data('basketQuantity', item.quantity.get())
                            .attr('data-basket-quantity', item.quantity.get());
                    };

                    item.update();

                    <?php if ($arResult['FORM']['SHOW']) { ?>
                        item.order.on('click', function () {
                            order(data);
                        });
                    <?php } ?>

                    <?php if ($arResult['ORDER_FAST']['USE']) { ?>
                        item.orderFast = $('[data-role="orderFast"]', item);
                        item.orderFast.on('click', function () {
                            var template = <?= JavaScript::toObject($arResult['ORDER_FAST']['TEMPLATE']) ?>;
                            var parameters = <?= JavaScript::toObject($arResult['ORDER_FAST']['PARAMETERS']) ?>;

                            parameters['PRODUCT'] = entity.id;
                            parameters['QUANTITY'] = item.quantity.get();

                            universe.components.show({
                                'component': 'intec.universe:sale.order.fast',
                                'template': template,
                                'parameters': parameters,
                                'settings': {
                                    'parameters': {
                                        'width': null
                                    }
                                }
                            });
                        });
                    <?php } ?>

                    item.quantity.on('change', function (event, value) {
                        item.update();
                    });

                    if (!item.offers.isEmpty()) {
                        item.properties = $('[data-role="item.property"]', item);
                        item.properties.values = $('[data-role="item.property.value"]', item.properties);
                        item.properties.attr('data-visible', 'false');
                        item.properties.each(function () {
                            var self = $(this);
                            var property = self.data('property');
                            var values = self.find(item.properties.values);

                            values.each(function () {
                                var self = $(this);
                                var value = self.data('value');

                                self.on('click', function () {
                                    item.offers.setCurrentByValue(property, value);
                                });
                            });
                        });

                        api.each(item.offers.getList(), function (index, offer) {
                            api.each(offer.values, function (key, value) {
                                if (value == 0)
                                    return;

                                item.properties
                                    .filter('[data-property=' + key + ']')
                                    .attr('data-visible', 'true');
                            });
                        });

                        item.offers.on('change', function (event, offer, values) {
                            entity = offer;

                            api.each(values, function (state, values) {
                                api.each(values, function (property, values) {
                                    property = item.properties.filter('[data-property="' + property + '"]');

                                    api.each(values, function (index, value) {
                                        value = property.find(item.properties.values).filter('[data-value="' + value + '"]');
                                        value.attr('data-state', state);
                                    });
                                });
                            });

                            item.update();
                        });

                        item.offers.setCurrentById(item.offers.getList()[0].id);
                    }

                    item.expand = function () {
                        var rectangle = item[0].getBoundingClientRect();
                        var height = rectangle.bottom - rectangle.top;

                        if (expanded)
                            return;

                        expanded = true;
                        item.css('height', height);
                        item.attr('data-expanded', 'true');
                    };

                    item.narrow = function () {
                        if (!expanded)
                            return;

                        expanded = false;
                        item.attr('data-expanded', 'false');
                        item.css('height', '');
                    };

                    item.stores.show = function () {
                        item.stores.toggleClass('active');
                    };

                    item.stores.hide = function () {
                        item.stores.removeClass('active');
                    };

                    item.price.extended.show = function () {
                        item.price.extended.toggleClass('active');
                    };

                    item.price.extended.hide = function () {
                        item.price.extended.removeClass('active');
                    };

                    item.on('mouseenter', item.expand)
                        .on('mouseleave', item.narrow)
                        .on('mouseleave', item.stores.hide)
                        .on('mouseleave', item.price.extended.hide);

                    item.offers.on('change', item.price.extended.hide);

                    $(window).on('resize', function () {
                        if (expanded) {
                            item.narrow();
                            item.expand();
                        }
                    });

                    item.stores.controls.toggle.on('click', item.stores.show);
                    item.stores.controls.close.on('click', item.stores.hide);

                    item.price.extended.toggle.on('click', item.price.extended.show);
                    item.price.extended.close.on('click', item.price.extended.hide);
                });

                <?php if ($arVisual['IMAGE']['SLIDER']) {

                    $arSlider = [
                        'items' => 1,
                        'nav' => $arVisual['IMAGE']['NAV'],
                        'dots' => $arVisual['IMAGE']['OVERLAY'],
                        'dotsEach' => $arVisual['IMAGE']['OVERLAY'] ? 1 : false,
                        'overlayNav' => $arVisual['IMAGE']['OVERLAY']
                    ];

                ?>
                    $(function () {
                        var slider = $('.owl-carousel', root);
                        var parameters = <?= JavaScript::toObject($arSlider) ?>

                            slider.owlCarousel({
                                'items': parameters.items,
                                'nav': parameters.nav,
                                'smartSpeed': 600,
                                'navText': [
                                    '<i class="far fa-chevron-left"></i>',
                                    '<i class="far fa-chevron-right"></i>'
                                ],
                                'dots': parameters.dots,
                                'dotsEach': parameters.dotsEach,
                                'overlayNav': parameters.overlayNav
                            });

                        <?php if ($arVisual['IMAGE']['OVERLAY']) { ?>

                        slider.dots = $('.owl-dots', slider);
                        slider.dots.dot = slider.dots.find('[role="button"]');
                        slider.dots.dot.active = slider.dots.dot.filter('.active');
                        slider.dots.addClass('intec-grid');
                        slider.dots.dot.addClass('intec-grid-item');
                        slider.dots.dot.active.find('span').addClass('intec-cl-background');

                        slider.on('changed.owl.carousel', function() {
                            slider.dots.dot = $('[role="button"]' , this);
                            slider.dots.dot.find('span').removeClass('intec-cl-background');
                            slider.dots.dot.filter('.active').find('span').addClass('intec-cl-background');
                        });

                        <?php } ?>
                    });
                <?php } ?>
            };

            root.update();
        });
    })(jQuery, intec);
</script>