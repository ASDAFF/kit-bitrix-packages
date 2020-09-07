<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\JavaScript;

/**
 * @var array $arResult
 * @var array $arVisual
 */

$arResult['JS_FILTER_PARAMS']['variable'] = 'smartFilter';

$arResult['JS_FILTER_PARAMS']['id'] = [
    'setFilter' => 'set_filter',
    'delFilter' => 'del_filter'
];

?>
<script>
    var <?= $arResult['JS_FILTER_PARAMS']['variable'] ?> = new JCSmartFilterHorizontal2(
        <?= JavaScript::toObject($arResult['FORM_ACTION']) ?>,
        <?= JavaScript::toObject($arVisual['VIEW']) ?>,
        <?= JavaScript::toObject($arResult['JS_FILTER_PARAMS'])?>
    );

    (function ($, api) {

        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
        var filter = {
            'body': $('[data-role="filter"]', root)
        };

        (function () {
            $(document).ready(function () {
                $('[data-role="bx_filter_box"]', filter.body).each(function(e){
                    var propertyName = $(this).find('[data-role="bx_filter_name"]'),
                        propertyBlock = $(this).find('[data-role="bx_filter_block"]'),
                        propertyNameWidth = propertyName.outerWidth(true),
                        propertyBlockWidth = 0;

                    propertyBlock.css({
                        'display': 'block',
                        'position': 'static'
                    });
                    propertyBlockWidth = propertyBlock.outerWidth(true) + 1;
                    propertyBlock.css({
                        'display': 'none',
                        'position': 'absolute'
                    });

                    if (propertyBlockWidth > propertyNameWidth) {
                        propertyNameWidth = propertyBlockWidth;
                    }

                    propertyName.css('width', propertyNameWidth + 'px');
                });
            });

            $('[data-role="bx_filter_name"]', filter.body).on('click', function(e){
                var item = $(this),
                    expanded = item.attr('data-expanded');

                if (!$(e.target).is('[data-role="prop_del"]')) {
                    if (expanded === 'true') {
                        item.attr('data-expanded', 'false');
                        smartFilter.hideFilterProps(item.get(0));
                    } else {
                        $('[data-role="bx_filter_box"]', filter.body).each(function(){
                            var element = $(this).find('[data-role="bx_filter_name"]'),
                                expanded = element.attr('data-expanded');

                            if (expanded === 'true') {
                                smartFilter.hideFilterProps(element.get(0));

                                element.attr('data-expanded', 'false');
                            }
                        });

                        smartFilter.hideFilterProps(this);
                        item.attr('data-expanded', 'true');
                    }
                }
            });

            $(':root').on('click', function (event) {
                if (!$(event.target).closest('[data-role="bx_filter_box"]').length > 0) {

                    $('[data-role="bx_filter_box"]', filter.body).each(function(){
                        var element = $(this).find('[data-role="bx_filter_name"]'),
                            expanded = element.attr('data-expanded');

                        if (expanded === 'true') {
                            smartFilter.hideFilterProps(element.get(0));

                            element.attr('data-expanded', 'false');
                        }
                    });
                }
            });

            checkedProperty = function(input) {
                var parentBox = input.closest('[data-role="bx_filter_box"]'),
                    propName = $('[data-role="bx_filter_name"]', parentBox),
                    propCount = $('[data-role="bx_filter_counter"]', propName),
                    propBlock = $('[data-role="bx_filter_block"]', parentBox),
                    propValues = $('[data-role="bx_filter_block"] input', parentBox),
                    propAngle = $('[data-role="prop_angle"]', parentBox),
                    propDelete = $('[data-role="prop_del"]', parentBox),
                    countCheckedInput = 0;

                if (propBlock.attr('data-property-type') === 'between') {
                    propValues.each(function () {
                        element = $(this);

                        if (element.val().length > 0)
                            countCheckedInput++;

                        if (countCheckedInput > 0) {
                            propName.addClass('smart-filter-property-name-active intec-cl-background intec-cl-border');
                            propAngle.hide();
                            propDelete.show();
                        } else {
                            propName.removeClass('smart-filter-property-name-active intec-cl-background intec-cl-border');
                            propAngle.show();
                            propDelete.hide();
                        }
                    });
                } else {
                    propValues.each(function () {
                        element = $(this);
                        if (element.is(':checked')) {
                            if (element.attr('type') === 'radio') {
                                if (element.val().length > 0)
                                    countCheckedInput++;
                            } else {
                                countCheckedInput++;
                            }
                        }
                    });

                    if (countCheckedInput > 0) {
                        propName.addClass('smart-filter-property-name-active intec-cl-background intec-cl-border');
                        propCount.html(countCheckedInput);
                        propAngle.hide();
                        propDelete.show();
                    } else {
                        propName.removeClass('smart-filter-property-name-active intec-cl-background intec-cl-border');
                        propCount.html('');
                        propAngle.show();
                        propDelete.hide();
                    }
                }
            };

            $('[data-role="prop_del"]').on('click', function() {
                var parentBox = $(this).closest('[data-role="bx_filter_box"]'),
                    propName = $('[data-role="bx_filter_name"]', parentBox),
                    propCount = $('[data-role="bx_filter_counter"]', propName),
                    propBlock = $('[data-role="bx_filter_block"]', parentBox),
                    propValues = $('[data-role="bx_filter_block"] input', parentBox),
                    propAngle = $('[data-role="prop_angle"]', parentBox),
                    propDelete = $('[data-role="prop_del"]', parentBox);

                if (propBlock.attr('data-property-type') === 'between') {
                    propValues.each(function () {
                        elem = $(this);
                        elem.val('');
                    });
                } else {
                    propValues.each(function () {
                        elem = $(this);
                        if (elem.is(':checked')) {
                            if (elem.attr('type') == 'radio') {
                                propValues.eq(0).trigger('click');
                            } else {
                                elem.trigger('click');
                            }
                        }
                    });
                }

                propName.removeClass('smart-filter-property-name-active intec-cl-background intec-cl-border');
                propCount.html('');
                propAngle.show();
                propDelete.hide();
            });

            $(document).ready(function(){
                $('[data-role="property.item.value"]:not(:disabled)', filter.body).on('click', function () {
                    checkedProperty($(this));
                });

                $('[data-role="property.item.value"]:not(:disabled)', filter.body).on('input', function () {
                    checkedProperty($(this));
                });

                $('[data-role="bx_filter_box"]', filter.body).each(function () {
                   var root = $(this);

                   $('[data-role="property.item.value"]', root).each(function () {
                       checkedProperty($(this));
                   });
                });
            });
        })();


    })(jQuery, intec);
</script>