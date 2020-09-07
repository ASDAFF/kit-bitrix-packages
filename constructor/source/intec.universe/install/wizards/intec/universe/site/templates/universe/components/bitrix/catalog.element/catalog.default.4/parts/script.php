<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use intec\core\helpers\JavaScript;

/**
 * @var array $arResult
 * @var string $sTemplateId
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

            window.offers = root.offers;

            root.article = $('[data-role="article"]', root);
            root.article.value = $('[data-role="article.value"]', root.article);
            root.gallery = $('[data-role="gallery"]', root);
            root.counter = $('[data-role="counter"]', root);
            root.price = $('[data-role="price"]', root);
            root.price.base = $('[data-role="price.base"]', root.price);
            root.price.measure = $('[data-role="price.measure"]', root.price);
            root.price.discount = $('[data-role="price.discount"]', root.price);
            root.price.percent = $('[data-role="price.percent"]', root.price);
            root.price.difference = $('[data-role="price.difference"]', root.price);
            root.price.title = $('[data-role="price.title"]', root.price);
            root.quantity = api.controls.numeric({
                'bounds': {
                    'minimum': entity.quantity.ratio,
                    'maximum': entity.quantity.trace && !entity.quantity.zero ? entity.quantity.value : false
                },
                'step': entity.quantity.ratio
            }, root.counter);

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

                if (article === null)
                    article = data.article;

                root.article.attr('data-show', article === null ? 'false' : 'true');
                root.article.value.text(article);

                api.each(entity.prices, function (index, object) {
                    if (object.quantity.from === null || root.quantity.get() >= object.quantity.from)
                        price = object;
                });

                if (price !== null) {
                    root.price.attr('data-discount', price.discount.use ? 'true' : 'false');
                    root.price.attr('data-extended', price.extended ? 'true' : 'false');
                    root.price.attr('data-measure', entity.quantity.measure !== null ? 'true' : 'false');
                    root.price.measure.text(entity.quantity.measure);
                    root.price.base.html(price.base.display);
                    root.price.discount.html(price.discount.display);
                    root.price.percent.text('- ' + price.discount.percent + '%');
                    root.price.difference.html(price.discount.difference);
                    root.price.title.text(price.title);
                } else {
                    root.price.attr('data-discount', 'false');
                    root.price.attr('data-extended', 'false');
                    root.price.attr('data-measure', 'false');
                    root.price.base.html(null);
                    root.price.discount.html(null);
                    root.price.percent.text(null);
                    root.price.difference.html(null);
                    root.price.title.text(null);
                }

                root.price.attr('data-show', price !== null ? 'true' : 'false');
                root.quantity.configure(quantity);
                //root.panel.quantity.configure(quantity);

                root.find('[data-offer]').css('display', '');

                if (entity !== data) {
                    root.find('[data-offer=' + entity.id + ']').css('display', 'block');
                    root.find('[data-offer="false"]').css('display', 'none');

                    if (root.gallery.filter('[data-offer=' + entity.id + ']').length === 0)
                        root.gallery.filter('[data-offer="false"]').css('display', '');
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
                        //root.panel.quantity.set(value);
                        root.update();
                        update = false;
                    }
                });

                /*root.panel.quantity.on('change', function (event, value) {
                    root.quantity.set(value);
                });*/
            })();

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

                                var child = value.find('[data-type]');

                                if (state === 'selected')
                                    child.addClass('intec-cl-border');
                                else
                                    child.removeClass('intec-cl-border');
                            });
                        });
                    });

                    root.update();
                });

                root.offers.setCurrentById(root.offers.getList()[0].id);
            }

            root.gallery.each(function () {
                var gallery = $(this);

                gallery.pictures = $('[data-role="gallery.pictures"]', gallery);
                gallery.picture = $('[data-role="gallery.picture"]', gallery.pictures);
                gallery.previews = $('[data-role="gallery.previews"]', gallery);
                gallery.preview = $('[data-role="gallery.preview"]', gallery.previews);
                gallery.action = gallery.data('action');
                gallery.zoom = gallery.data('zoom');

                gallery.pictures.owlCarousel({
                    'items': 1,
                    'nav': false,
                    'dots': false,
                    'onTranslate': function (event) {
                        var index = event.item.index;

                        gallery.previews.trigger('to.owl.carousel', index);

                        gallery.preview
                            .attr('data-active', 'false')
                            .eq(index)
                            .attr('data-active', 'true');
                    }
                });

                gallery.previews.owlCarousel({
                    'items': 4,
                    'nav': true,
                    'navText': [
                        '<span class="far fa-chevron-left"></span>',
                        '<span class="far fa-chevron-right"></span>'
                    ],
                    'navClass': [
                        'preview-prev intec-cl-text-hover',
                        'preview-next intec-cl-text-hover'
                    ],
                    'navContainer': $('[data-role="gallery.previews.nav"]', gallery),
                    'dots': false,
                    'responsive': {
                        '0': {
                            'items': 3
                        },
                        '376': {
                            'items': 5
                        },
                        '769': {
                            'items': 3
                        },
                        '1025': {
                            'items': 4
                        }
                    }
                });

                gallery.preview.on('click', function () {
                    var item = $(this);
                    var index = item.parent('.owl-item').index();
                    var active = item.attr('data-active') === 'true';

                    if (!active)
                        gallery.pictures.trigger('to.owl.carousel', index);
                });

                if (gallery.action === 'popup') {
                    gallery.pictures.lightGallery({
                        'share': false,
                        'selector': '[data-role="gallery.picture"]'
                    });
                }

                if (gallery.zoom) {
                    gallery.picture.each(function () {
                        var picture = $(this);
                        var source = picture.data('src');

                        picture.zoom({
                            'url': source,
                            'touch': false
                        });
                    });
                }
            });

            <?php if ($arResult['ORDER_FAST']['USE']) { ?>
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
        });
    })(jQuery, intec);
</script>
