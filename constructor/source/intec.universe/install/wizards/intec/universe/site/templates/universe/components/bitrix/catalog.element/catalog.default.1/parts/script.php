<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $sTemplateId
 * @var array $arVisual
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 */

$arResult['ORDER_FAST']['PARAMETERS']['AJAX_MODE'] = 'Y';
$arResult['ORDER_FAST']['PARAMETERS']['AJAX_OPTION_ADDITIONAL'] = $sTemplateId.'-order-fast';

?>
<script type="text/javascript">
    (function ($, api) {
        $(function () {
            var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
            var properties = root.data('properties');
            var data = root.data('data');
            var entity = data;

            root.offers = new universe.catalog.offers({
                'properties': properties,
                'list': data.offers
            });

            window.offers = root.offers;

            root.gallery = $('[data-role="gallery"]', root);
            root.gallery.pictures = $('[data-role="gallery.pictures"]', root.gallery);
            root.gallery.pictures.items = $('[data-role="gallery.picture"]', root.gallery.pictures);
            root.gallery.previews = $('[data-role="gallery.previews"]', root.gallery);
            root.gallery.previews.items = $('[data-role="gallery.preview"]', root.gallery.previews);
            root.article = $('[data-role="article"]', root);
            root.article.value = $('[data-role="article.value"]', root.article);
            root.counter = $('[data-role="counter"]', root);
            root.price = $('[data-role="price"]', root);
            root.price.base = $('[data-role="price.base"]', root.price);
            root.price.discount = $('[data-role="price.discount"]', root.price);
            root.price.difference = $('[data-role="price.difference"]', root.price);
            root.quantity = api.controls.numeric({
                'bounds': {
                    'minimum': entity.quantity.ratio,
                    'maximum': entity.quantity.trace && !entity.quantity.zero ? entity.quantity.value : false
                },
                'step': entity.quantity.ratio
            }, root.counter);
            root.panel = $('[data-role="panel"]', root);
            root.panel.picture = $('[data-role="panel.picture"]', root.panel);
            root.panel.counter = $('[data-role="panel.counter"]', root.panel);
            root.panel.quantity = api.controls.numeric({}, root.panel.counter);
            root.panelMobile = $('[data-role="panel.mobile"]', root);
            root.purchase = $('[data-role="purchase"]', root);

            <?php if (empty($arResult['OFFERS']) || $arResult['SKU_VIEW'] == 'dynamic') { ?>
                root.update = function () {
                    var article = entity.article;
                    var price = null;
                    var quantity = {
                        'bounds': {
                            'minimum': entity.quantity.ratio,
                            'maximum': entity.quantity.trace && !entity.quantity.zero ? entity.quantity.value : false
                        },
                        'step': entity.quantity.ratio
                    };

                    root.attr('data-available', entity.available ? 'true' : 'false');
                    root.attr('data-subscribe', entity.subscribe ? 'true' : 'false');

                    if (article == null)
                        article = data.article;

                    root.article.attr('data-show', article == null ? 'false' : 'true');
                    root.article.value.text(article);

                    api.each(entity.prices, function (index, object) {
                        if (object.quantity.from === null || root.quantity.get() >= object.quantity.from)
                            price = object;
                    });

                    if (price !== null) {
                        root.price.attr('data-discount', price.discount.use ? 'true' : 'false');
                        root.price.base.html(price.base.display);
                        root.price.discount.html(price.discount.display);
                        root.price.difference.html(price.discount.difference);
                    } else {
                        root.price.attr('data-discount', 'false');
                        root.price.base.html(null);
                        root.price.discount.html(null);
                        root.price.difference.html(null);
                    }

                    root.price.attr('data-show', price !== null ? 'true' : 'false');
                    root.quantity.configure(quantity);
                    root.panel.quantity.configure(quantity);

                    root.find('[data-offer]').css('display', '');

                    if (entity !== data) {
                        root.find('[data-offer=' + entity.id + ']').css('display', 'block');
                        root.find('[data-offer="false"]').css('display', 'none');

                        if (root.gallery.filter('[data-offer=' + entity.id + ']').length === 0)
                            root.gallery.filter('[data-offer="false"]').css('display', '');

                        if (root.panel.picture.filter('[data-offer=' + entity.id + ']').length === 0)
                            root.panel.picture.filter('[data-offer="false"]').css('display', '');
                    }

                    root.find('[data-basket-id]')
                        .data('basketQuantity', root.quantity.get())
                        .attr('data-basket-quantity', root.quantity.get());
                };

                root.update();

                (function () {
                    var update = false;

                    root.quantity.on('change', function (event, value) {
                        if (!update) {
                            update = true;
                            root.panel.quantity.set(value);
                            root.update();
                            update = false;
                        }
                    });

                    root.panel.quantity.on('change', function (event, value) {
                        root.quantity.set(value);
                    });
                })();
            <?php } ?>

            <?php if ($arResult['SKU_VIEW'] == 'dynamic') { ?>
                if (!root.offers.isEmpty()) {
                    root.properties = $('[data-role="property"]', root);
                    root.properties.values = $('[data-role="property.value"]', root.properties);
                    root.properties.each(function () {
                        var self = $(this);
                        var property = self.data('property');
                        var values = self.find(root.properties.values);

                        values.each(function () {
                            var self = $(this);
                            var value = self.data('value');

                            self.on('click', function () {
                                root.offers.setCurrentByValue(property, value);
                            });
                        });
                    });

                    root.offers.on('change', function (event, offer, values) {
                        entity = offer;

                        api.each(values, function (state, values) {
                            api.each(values, function (property, values) {
                                property = root.properties.filter('[data-property="' + property + '"]');

                                api.each(values, function (index, value) {
                                    value = property.find(root.properties.values).filter('[data-value="' + value + '"]');
                                    value.attr('data-state', state);
                                });
                            });
                        });

                        root.update();
                    });

                    root.offers.setCurrentById(root.offers.getList()[0].id);
                }
            <?php } ?>

            root.gallery.each(function () {
                var gallery = $(this);
                var pictures;
                var previews;

                pictures = gallery.find(root.gallery.pictures);
                pictures.items = pictures.find(root.gallery.pictures.items);
                previews = gallery.find(root.gallery.previews);
                previews.items = previews.find(root.gallery.previews.items);

                pictures.owlCarousel({
                    'items': 1,
                    'nav': false,
                    'dots': false
                });

                <?php if ($arVisual['GALLERY']['POPUP']) { ?>
                    pictures.lightGallery({
                        'share': false,
                        'selector': '.catalog-element-gallery-picture'
                    });
                <?php } ?>

                <?php if ($arVisual['GALLERY']['ZOOM']) { ?>
                    pictures.items.each(function () {
                        var picture = $(this);
                        var source = picture.data('src');

                        picture.zoom({
                            'url': source,
                            'touch': false
                        });
                    });
                <?php } ?>

                <?php if ($arVisual['GALLERY']['SLIDER']) { ?>
                    previews.owlCarousel({
                        'items': 6
                    });

                    previews.set = function (number) {
                        previews.items.attr('data-active', 'false');
                        previews.items.eq(number).attr('data-active', 'true');
                    };

                    previews.items.on('click', function () {
                        var preview = $(this);
                        var previewIndex = preview.parent('.owl-item').index();

                        pictures.trigger('to.owl.carousel', [previewIndex]);
                        previews.set(previewIndex);
                    });

                    pictures.on('changed.owl.carousel', function (event) {
                        previews.set(event.item.index);
                    });
                <?php } ?>
            });

            <?php if ($arResult['FORM']['ORDER']['SHOW']) { ?>
                root.order = $('[data-role="order"]', root);
                root.order.on('click', function () {
                    var options = <?= JavaScript::toObject([
                        'id' => $arResult['FORM']['ORDER']['ID'],
                        'template' => $arResult['FORM']['ORDER']['TEMPLATE'],
                        'parameters' => [
                            'AJAX_OPTION_ADDITIONAL' => $sTemplateId.'-form',
                            'CONSENT_URL' => $arResult['URL']['CONSENT']
                        ],
                        'settings' => [
                            'title' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_FORM_TITLE')
                        ]
                    ]) ?>;

                    options.fields = {};

                    <?php if (!empty($arResult['FORM']['ORDER']['PROPERTIES']['PRODUCT'])) { ?>
                    options.fields[<?= JavaScript::toObject($arResult['FORM']['ORDER']['PROPERTIES']['PRODUCT']) ?>] = data.name;
                    <?php } ?>

                    universe.forms.show(options);

                    if (window.yandex && window.yandex.metrika) {
                        window.yandex.metrika.reachGoal('forms.open');
                        window.yandex.metrika.reachGoal(<?= JavaScript::toObject('forms.'.$arResult['FORM']['ORDER']['ID'].'.open') ?>);
                    }
                });
            <?php } ?>

            <?php if ($arResult['FORM']['CHEAPER']['SHOW']) { ?>
            root.cheaper = $('[data-role="cheaper"]', root);
            root.cheaper.on('click', function () {
                var options = <?= JavaScript::toObject([
                    'id' => $arResult['FORM']['CHEAPER']['ID'],
                    'template' => $arResult['FORM']['CHEAPER']['TEMPLATE'],
                    'parameters' => [
                        'AJAX_OPTION_ADDITIONAL' => $sTemplateId.'-form',
                        'CONSENT_URL' => $arResult['URL']['CONSENT']
                    ],
                    'settings' => [
                        'title' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_1_FORM_CHEAPER_TITLE')
                    ]
                ]) ?>;

                options.fields = {};

                <?php if (!empty($arResult['FORM']['CHEAPER']['PROPERTIES']['PRODUCT'])) { ?>
                options.fields[<?= JavaScript::toObject($arResult['FORM']['CHEAPER']['PROPERTIES']['PRODUCT']) ?>] = data.name;
                <?php } ?>

                universe.forms.show(options);

                if (window.yandex && window.yandex.metrika) {
                    window.yandex.metrika.reachGoal('forms.open');
                    window.yandex.metrika.reachGoal(<?= JavaScript::toObject('forms.'.$arResult['FORM']['CHEAPER']['ID'].'.open') ?>);
                }
            });
            <?php } ?>

            <?php if ($arResult['ORDER_FAST']['USE'] &&
                        (empty($arResult['OFFERS']) || $arResult['SKU_VIEW'] == 'dynamic')) { ?>
                    root.orderFast = $('[data-role="orderFast"]', root);
                    root.orderFast.on('click', function () {
                        var template = <?= JavaScript::toObject($arResult['ORDER_FAST']['TEMPLATE']) ?>;
                        var parameters = <?= JavaScript::toObject($arResult['ORDER_FAST']['PARAMETERS']) ?>;

                        parameters['PRODUCT'] = entity.id;
                        parameters['QUANTITY'] = root.quantity.get();

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

            if (root.panel.length === 1) (function () {
                var state = false;
                var area = $(window);
                var update;
                var panel;

                update = function () {
                    var bound = 0;

                    if (root.is(':visible')) {
                        bound += root.offset().top;
                    }

                    if (area.scrollTop() > bound) {
                        panel.show();
                    } else {
                        panel.hide();
                    }
                };

                panel = root.panel;
                panel.css({
                    'top': -panel.height()
                });

                panel.show = function () {
                    if (state) return;

                    state = true;
                    panel.css({
                        'display': 'block'
                    });

                    panel.trigger('show');
                    panel.stop().animate({
                        'top': 0
                    }, 500)
                };

                panel.hide = function () {
                    if (!state) return;

                    state = false;
                    panel.stop().animate({
                        'top': -panel.height()
                    }, 500, function () {
                        panel.trigger('hide');
                        panel.css({
                            'display': 'none'
                        })
                    })
                };

                update();

                area.on('scroll', update)
                    .on('resize', update);
            })();

            if (root.panelMobile.length === 1 && root.purchase.length === 1) (function () {
                var state = false;
                var area = $(window);
                var update;
                var panel;

                update = function () {
                    var bound = root.purchase.offset().top;

                    if (area.scrollTop() > bound) {
                        panel.show();
                    } else {
                        panel.hide();
                    }
                };

                panel = root.panelMobile;
                panel.css({
                    'bottom': -panel.outerHeight()
                });

                panel.show = function () {
                    if (state) return;

                    state = true;
                    panel.css({
                        'display': 'block'
                    });

                    panel.trigger('show');
                    panel.stop().animate({
                        'bottom': 0
                    }, 500)
                };

                panel.hide = function () {
                    if (!state) return;

                    state = false;
                    panel.stop().animate({
                        'bottom': -panel.outerHeight()
                    }, 500, function () {
                        panel.trigger('hide');
                        panel.css({
                            'display': 'none'
                        })
                    })
                };

                update();

                area.on('scroll', update)
                    .on('resize', update);
            })();

            <?php if (!empty($arResult['OFFERS']) && $arResult['SKU_VIEW'] == 'list') { ?>
                root.offers = $('[data-role="offers"]', root);
                root.offers.list = $('[data-role="offer"]', root.offers);

                root.offers.list.each(function () {

                    var offer = $(this);
                    var dataOffer = offer.data('offer-data');

                    offer.counter = $('[data-role="counter"]', offer);
                    offer.quantity = api.controls.numeric({
                        'bounds': {
                            'minimum': dataOffer.quantity.ratio,
                            'maximum': dataOffer.quantity.trace && !dataOffer.quantity.zero ? dataOffer.quantity.value : false
                        },
                        'step': dataOffer.quantity.ratio
                    }, offer.counter);

                    offer.price = $('[data-role="price"]', offer);
                    offer.price.base = $('[data-role="price.base"]', offer.price);
                    offer.price.discount = $('[data-role="price.discount"]', offer.price);
                    offer.price.difference = $('[data-role="price.difference"]', offer.price);

                    offer.update = function () {

                        var price = null;

                        api.each(dataOffer.prices, function (index, object) {
                            if (object.quantity.from === null || offer.quantity.get() >= object.quantity.from)
                                price = object;
                        });

                        if (price !== null) {
                            offer.price.attr('data-discount', price.discount.use ? 'true' : 'false');
                            offer.price.base.html(price.base.display);
                            offer.price.discount.html(price.discount.display);
                            offer.price.difference.html(price.discount.difference);
                        } else {
                            offer.price.attr('data-discount', 'false');
                            offer.price.base.html(null);
                            offer.price.discount.html(null);
                            offer.price.difference.html(null);
                        }

                        offer.find('[data-basket-id]')
                            .data('basketQuantity', offer.quantity.get())
                            .attr('data-basket-quantity', offer.quantity.get());
                    }

                    offer.quantity.on('change', function (event, value) {
                        offer.update();
                    });

                    <?php if ($arResult['ORDER_FAST']['USE']) { ?>

                        offer.orderFast = $('[data-role="orderFast"]', offer);

                        offer.orderFast.on('click', function () {
                            var template = <?= JavaScript::toObject($arResult['ORDER_FAST']['TEMPLATE']) ?>;
                            var parameters = <?= JavaScript::toObject($arResult['ORDER_FAST']['PARAMETERS']) ?>;

                            parameters['PRODUCT'] = dataOffer.id;
                            parameters['QUANTITY'] = offer.quantity.get();

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
                });
            <?php } ?>

            <?php if ($arVisual['PRINT']['SHOW']) { ?>
                var print = $('[data-role="print"]', root);

                print.on('click', function () {
                    window.print();
                });
            <?php } ?>
        });
    })(jQuery, intec);
</script>