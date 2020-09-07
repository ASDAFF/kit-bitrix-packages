<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\JavaScript;

/**
 * @var string $sTemplateId
 * @var array $arResult
 * @var array $arVisual
 */

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

            root.gallery = $('[data-role="gallery"]', root);
            root.gallery.preview = $('[data-role="gallery.preview"]', root.gallery);
            root.article = $('[data-role="article"]', root);
            root.article.value = $('[data-role="article.value"]', root.article);
            root.counter = $('[data-role="counter"]', root);
            root.price = $('[data-role="price"]', root);
            root.price.base = $('[data-role="price.base"]', root.price);
            root.price.discount = $('[data-role="price.discount"]', root.price);
            root.price.percent = $('[data-role="price.percent"]', root.price);
            root.price.difference = $('[data-role="price.difference"]', root.price);
            root.quantity = api.controls.numeric({
                'bounds': {
                    'minimum': entity.quantity.ratio,
                    'maximum': entity.quantity.trace && !entity.quantity.zero ? entity.quantity.value : false
                },
                'step': entity.quantity.ratio
            }, root.counter);
            root.section = $('[data-role="section"]', root);
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
                        root.price.percent.text('-' + price.discount.percent + '%');
                        root.price.difference.html(price.discount.difference);
                    } else {
                        root.price.attr('data-discount', 'false');
                        root.price.base.html(null);
                        root.price.discount.html(null);
                        root.price.percent.text(null);
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
                var panel;
                var preview;

                preview = $('[data-role="gallery.preview"]', gallery);
                preview.items = $('[data-role="gallery.preview.item"]', preview);
                preview.popup = $('[data-role="gallery.preview.popup"]', preview);
                pictures = $('[data-role="gallery.pictures"]', gallery);
                pictures.items = $('[data-role="gallery.picture"]', pictures);
                panel = $('[data-role="gallery.panel"]', gallery);
                panel.buttons = {
                    'popup': $('[data-role="gallery.popup"]', panel),
                    'previous': $('[data-role="gallery.previous"]', panel),
                    'next': $('[data-role="gallery.next"]', panel),
                    'play': $('[data-role="gallery.play"]', panel)
                };

                panel.current = $('[data-role="gallery.current"]', panel);
                panel.current.set = function (number) {
                    this.value = number;
                    this.text(number + '/' + pictures.items.length);
                };

                <?php if ($arVisual['GALLERY']['PREVIEW']) { ?>
                    preview.set = function (number) {
                        preview.items.attr('data-active', 'false');
                        preview.items.eq(number).attr('data-active', 'true');
                    };
                    preview.items.on('click', function () {
                        var self = $(this);

                        pictures.trigger('to.owl.carousel', [self.index()]);
                    });
                    preview.popup.on('click', function () {
                        var control = pictures.data('lightGallery');

                        control.init();
                        control.index = panel.current.value - 1;
                        control.build(control.index);
                    });

                    preview.set(0);
                <?php } ?>

                pictures.owlCarousel({
                    'items': 1,
                    'nav': false,
                    'dots': false
                });
                pictures.on('changed.owl.carousel', function (event) {
                    panel.current.set(event.item.index + 1);
                    <?php if ($arVisual['GALLERY']['PREVIEW']) { ?>
                        preview.set(event.item.index);
                    <?php } ?>
                });

                panel.buttons.previous.on('click', function () {
                    pictures.trigger('prev.owl.carousel');
                });
                panel.buttons.next.on('click', function () {
                    pictures.trigger('next.owl.carousel');
                });
                panel.current.set(1);

                <?php if ($arVisual['GALLERY']['POPUP']) { ?>
                    pictures.lightGallery({
                        'share': false,
                        'selector': '[data-role="gallery.picture"]'
                    });

                    panel.buttons.popup.on('click', function () {
                        var control = pictures.data('lightGallery');

                        control.init();
                        control.index = panel.current.value - 1;
                        control.build(control.index);
                    });

                    panel.buttons.play.on('click', function () {
                        var control = pictures.data('lightGallery');

                        control.init();
                        control.index = panel.current.value - 1;
                        control.build(control.index);
                        control.modules.autoplay.startlAuto();
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
            });

            root.section.each(function () {
                var section = $(this);
                var state = section.data('expanded');

                section.name = $('[data-role="section.name"]', section);
                section.content = $('[data-role="section.content"]', section);

                if (!api.isBoolean(state))
                    state = true;

                section.open = function (animate) {
                    if (state)
                        return;

                    state = true;

                    if (animate) {
                        var height = {
                            'current': section.content.height(),
                            'new': section.content.css('height', '').height()
                        };

                        section.content.css('height', height.current);
                        section.content.stop().animate({'height': height.new, 'opacity': 1}, 500, function () {
                            section.content.css('height', '');
                            section.attr('data-expanded', 'true');
                        });
                    } else {
                        section.content.css('height', '');
                    }
                };

                section.close = function (animate) {
                    if (!state)
                        return;

                    state = false;
                    section.attr('data-expanded', 'false');

                    if (animate) {
                        section.content.stop().animate({'height': 0, 'opacity': 0}, 500, function () {
                            section.attr('data-expanded', 'false');
                        });
                    } else {
                        section.content.css('height', 0);
                    }
                };

                section.toggle = function (animate) {
                    if (state) {
                        section.close(animate);
                    } else {
                        section.open(animate);
                    }
                };

                state = !state;
                section.toggle(false);

                section.name.on('click', function () {
                    section.toggle(true);
                });
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
                            'title' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_FORM_TITLE')
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
                            'title' => Loc::getMessage('C_CATALOG_ELEMENT_CATALOG_DEFAULT_3_FORM_CHEAPER_TITLE')
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