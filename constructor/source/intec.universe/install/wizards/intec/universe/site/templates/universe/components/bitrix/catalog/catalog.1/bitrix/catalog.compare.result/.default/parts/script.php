<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
<?php

use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $sTemplateId
 */

?>
<script type="text/javascript">
    (function ($, api) {
        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var component = new BX.Iblock.Catalog.CompareClass(
            <?= JavaScript::toObject($sTemplateId) ?>,
            <?= JavaScript::toObject($arResult['~COMPARE_URL_TEMPLATE']) ?>
        );

        var responsive = {
            '0': {
                'items': 1
            },
            '501': {
                'items': 2
            },
            '701': {
                'items': 3
            },
            '951': {
                'items': 4
            },
            '1201': {
                'items': 5
            }
        };

        var difference = $('[data-role="difference"]', root);
        var clear = $('[data-role="clear"]', root);
        var slider = $('[data-role="slider"]', root);
        var navigation = $('[data-role="navigation"]', root);
        var labels = $('[data-role="labels"]', root);
        var properties = $('[data-role="properties"]', root);

        if (difference.length !== 0) {
            difference.locked = false;
            difference.input = difference.find('input');
            difference.on('click', function () {
                if (difference.locked)
                    return;

                difference.locked = true;
                difference.input.prop('checked', !difference.input.prop('checked'));
                document.location.href = difference.attr('data-action');
            });
        }

        if (clear.length !== 0) {
            difference.locked = false;
            clear.on('click', function () {
                if (clear.locked)
                    return;

                clear.locked = true;
                universe.compare.clear({
                    'code': <?= JavaScript::toObject($arParams['NAME']) ?>
                }, function () {
                    document.location.reload();
                });
            });
        }

        if (slider.length !== 0) {
            slider.getItems = function () {
                return $('.owl-item', slider);
            };

            slider.on('dragged.owl.carousel translated.owl.carousel', function (event) {
                properties.trigger('to.owl.carousel', [event.item.index, 250]);
            });

            slider.on('initialized.owl.carousel resized.owl.carousel refreshed.owl.carousel', function (event) {
                navigation.attr('data-enabled', event.item.count > event.page.size ? 'true' : 'false');
            });

            slider.on('click', function (event) {
                var target = $(event.target);
                var button = target.closest('[data-role="item.remove"]', slider);

                if (button.length !== 0)
                    component.MakeAjaxAction(button.attr('data-action'));
            });

            slider.owlCarousel({
                'items': 5,
                'loop': false,
                'rewind': true,
                'nav': false,
                'dots': false,
                'dragEndSpeed': 500,
                'stagePadding': 1,
                'responsive': responsive
            });
        }

        if (navigation.length !== 0) {
            navigation.getButtons = function () {
                return $('[data-role="navigation.button"]', navigation);
            };

            navigation.getButtons().on('click', function () {
                var button = $(this);
                var action = button.attr('data-action');

                if (action === 'next') {
                    slider.trigger('next.owl.carousel');
                    properties.trigger('next.owl.carousel');
                } else if (action === 'previous') {
                    slider.trigger('prev.owl.carousel');
                    properties.trigger('prev.owl.carousel');
                }
            });
        }

        if (labels.length !== 0) {
            labels.getItems = function () {
                return $('[data-role="label"]', labels);
            };

            labels.on('click', function (event) {
                var target = $(event.target);
                var button = target.closest('[data-role="label"]', properties);

                if (button.length !== 0)
                    component.MakeAjaxAction(button.attr('data-action'));
            });
        }

        if (properties.length !== 0) {
            properties.getItems = function () {
                return $('.owl-item', properties);
            };

            properties.on('dragged.owl.carousel translated.owl.carousel', function (event) {
                slider.trigger('to.owl.carousel', [event.item.index, 250]);
            });

            properties.on('initialized.owl.carousel dragged.owl.carousel translated.owl.carousel resized.owl.carousel refreshed.owl.carousel', function (event) {
                var items = properties.getItems();
                var index = event.item.index;

                if (items.length === 0)
                    return;

                items.attr('data-first', 'false');
                items.attr('data-last', 'false');
                items.eq(index).attr('data-first', 'true');

                index = event.item.index + event.page.size - 1;

                if (index > items.length - 1)
                    index = items.length - 1;

                items.eq(index).attr('data-last', 'true');
            });

            properties.on('initialized.owl.carousel resized.owl.carousel refreshed.owl.carousel', function (event) {
                var items = properties.getItems();

                if (items.length === 0)
                    return;

                items.eq(0).find('[data-role="property"]').each(function () {
                    var property = $(this);
                    var properties = items.find('[data-role="property"]:eq(' + property.index() + ')');
                    var height = 0;

                    properties.css('height', '');
                    properties.each(function () {
                        var property = $(this);

                        if (height < property.outerHeight(false))
                            height = property.outerHeight(false);
                    });

                    properties.css('height', height);
                });
            });

            properties.on('mouseover', function (event) {
                var target = $(event.target);
                var items;

                if (target.is('[data-role="property"]')) {
                    items = properties.getItems();
                    items.find('[data-role="property"]').attr('data-state', 'none');
                    items.find('[data-role="property"]:eq(' + target.index() + ')').attr('data-state', 'hover');
                }
            }).on('mouseleave', function () {
                properties.getItems().find('[data-role="property"]').attr('data-state', 'none');
            }).on('click', function (event) {
                var target = $(event.target);
                var button = target.closest('[data-role="property.remove"]', properties);

                if (button.length !== 0)
                    component.MakeAjaxAction(button.attr('data-action'));
            });

            properties.owlCarousel({
                'items': 5,
                'loop': false,
                'rewind': true,
                'nav': false,
                'dots': false,
                'dragEndSpeed': 500,
                'stagePadding': 1,
                'responsive': responsive
            });
        }
    })(jQuery, intec);
</script>
