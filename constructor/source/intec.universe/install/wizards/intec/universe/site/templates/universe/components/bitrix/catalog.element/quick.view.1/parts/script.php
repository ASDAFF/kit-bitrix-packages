<?php if (!defined('B_PROLOG_INCLUDED') && B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\JavaScript;

/**
 * @var string $sTemplateId
 * @var array $arVisual
 */

?>
<script type="text/javascript">
    (function ($, api) {
        universe.basket.update();

        $(function () {
            var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
            var properties = root.data('properties');
            var data = root.data('data');
            var entity = data;

            root.scrollbar();
            $('[data-scroll="true"]', root).scrollbar({
                'disableBodyScroll': true
            });

            root.offers = new universe.catalog.offers({
                'properties': properties,
                'list': data.offers
            });

            root.weight = $('[data-role="weight"]', root);
            root.gallery = $('[data-role="gallery"]', root);
            root.gallery.previews = $('[data-role="gallery.previews"]', root.gallery);
            root.gallery.previews.items = $('[data-role="gallery.preview"]', root.gallery.previews);
            root.article = $('[data-role="article"]', root);
            root.article.value = $('[data-role="article.value"]', root.article);
            root.counter = $('[data-role="counter"]', root);
            root.price = $('[data-role="price"]', root);
            root.price.base = $('[data-role="price.base"]', root.price);
            root.price.discount = $('[data-role="price.discount"]', root.price);
            root.price.percent = $('[data-role="price.percent"]', root.price);
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
                } else {
                    root.price.attr('data-discount', 'false');
                    root.price.base.html(null);
                    root.price.discount.html(null);
                    root.price.percent.text(null);
                }

                root.price.attr('data-show', price !== null ? 'true' : 'false');
                root.quantity.configure({
                    'bounds': {
                        'minimum': entity.quantity.ratio,
                        'maximum': entity.quantity.trace && !entity.quantity.zero ? entity.quantity.value : false
                    },
                    'step': entity.quantity.ratio
                });

                root.find('[data-offer]').css('display', '');

                if (entity.quantity.weight)
                    root.weight.text(entity.quantity.weight);
                else
                    root.weight.text('');

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
            root.quantity.on('change', function (event, value) {
                root.update();
            });

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

            root.gallery.each(function () {
                var gallery = $(this);
                var pictures;
                var panel;
                var previews;

                pictures = $('[data-role="gallery.pictures"]', gallery);
                pictures.items = $('[data-role="gallery.picture"]', pictures);
                panel = $('[data-role="gallery.panel"]', gallery);
                panel.buttons = {
                    'previous': $('[data-role="gallery.previous"]', panel),
                    'next': $('[data-role="gallery.next"]', panel),
                };
                previews = $('[data-role="gallery.previews"]', gallery);
                previews.items = $('[data-role="gallery.preview"]', previews);

                panel.current = $('[data-role="gallery.current"]', panel);
                panel.current.set = function (number) {
                    this.value = number;
                    this.text(number + '/' + pictures.items.length);
                };

                pictures.owlCarousel({
                    'items': 1,
                    'nav': false,
                    'dots': false
                });

                previews.set = function (number) {
                    var item = previews.items.eq(number);

                    previews.items.attr('data-active', 'false');
                    item.attr('data-active', 'true');
                };
                previews.items.on('click', function () {
                    var preview = $(this);

                    pictures.trigger('to.owl.carousel', [preview.index()]);
                    previews.set(preview.index());
                });
                previews.set(0);

                pictures.on('changed.owl.carousel', function (event) {
                    panel.current.set(event.item.index + 1);
                    previews.set(event.item.index);
                });

                panel.buttons.previous.on('click', function () {
                    pictures.trigger('prev.owl.carousel');
                });

                panel.buttons.next.on('click', function () {
                    pictures.trigger('next.owl.carousel');
                });

                panel.current.set(1);
            });
        });
    })(jQuery, intec);
</script>