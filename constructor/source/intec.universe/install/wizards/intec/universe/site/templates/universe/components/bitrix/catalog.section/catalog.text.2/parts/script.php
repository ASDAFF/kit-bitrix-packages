<?php if (defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED === true) ?>
    <?php

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $sTemplateId
 * @var string $sTemplateContainer
 * @var array $arVisual
 * @var array $arNavigation
 * @var CBitrixComponent $component
 * @var CBitrixComponentTemplate $this
 */

$oSigner = new \Bitrix\Main\Security\Sign\Signer;
$sSignedTemplate = $oSigner->sign($templateName, 'catalog.section');
$sSignedParameters = $oSigner->sign(base64_encode(serialize($arResult['ORIGINAL_PARAMETERS'])), 'catalog.section');

?>
    <script type="text/javascript">
        (function ($, api) {
            $(function () {
                var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
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
                            'title' => Loc::getMessage('C_CATALOG_SECTION_CATALOG_TEXT_2_FORM_TITLE')
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

                        if (handled.indexOf(this) > -1)
                            return;

                        handled.push(this);
                        item.offers = new universe.catalog.offers({
                            'properties': properties.length !== 0 ? properties : data.properties,
                            'list': data.offers
                        });

                        item.counter = $('[data-role="item.counter"]', item);
                        item.price = $('[data-role="item.price"]', item);
                        item.price.measure = $('[data-role="price.measure"]', item.price);
                        item.price.base = $('[data-role="item.price.base"]', item.price);
                        item.price.discount = $('[data-role="item.price.discount"]', item.price);
                        item.price.percent = $('[data-role="item.price.percent"]', item.price);
                        item.order = $('[data-role="item.order"]', item);
                        item.quantity = api.controls.numeric({
                            'bounds': {
                                'minimum': entity.quantity.ratio,
                                'maximum': entity.quantity.trace && !entity.quantity.zero ? entity.quantity.value : false
                            },
                            'step': entity.quantity.ratio
                        }, item.counter);
                        item.toggle = $('[data-role="item.toggle"]', item);
                        item.toggleButtons = $('[data-toggle]', item);
                        item.toggleButtons.open = $('[data-toggle="open"]', item);
                        item.toggleButtons.close = $('[data-toggle="close"]', item);

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

                        item.toggle.open = function (timer) {
                            item.toggle.slideDown(timer);
                            item.toggleButtons.close.addClass('active');
                            item.toggleButtons.open.removeClass('active');
                        };

                        item.toggle.close = function (timer) {
                            item.toggle.slideUp(timer);
                            item.toggleButtons.open.addClass('active');
                            item.toggleButtons.close.removeClass('active');
                        };

                        item.toggleButtons.open.click(item.toggle.open);
                        item.toggleButtons.close.click(item.toggle.close);

                        item.update = function () {
                            var price = null;

                            item.attr('data-available', entity.available ? 'true' : 'false');
                            api.each(entity.prices, function (index, object) {
                                if (object.quantity.from === null || item.quantity.get() >= object.quantity.from)
                                    price = object;
                            });

                            if (price !== null) {
                                item.price.attr('data-discount', price.discount.use ? 'true' : 'false');
                                item.price.attr('data-measure', price.discount.measure !== null ? 'true' : 'false');
                                item.price.attr('data-range', ((price.quantity.from !== null) || (price.quantity.to !== null)) ? 'true' : 'false');
                                item.price.measure.text(price.discount.measure);
                                item.price.base.html(price.base.display);
                                item.price.discount.html(price.discount.display);
                                item.price.percent.text('-' + price.discount.percent + '%');
                            } else {
                                item.price.attr('data-discount', 'false');
                                item.price.attr('data-measure', 'false');
                                item.price.attr('data-range', 'false');
                                item.price.base.html(null);
                                item.price.discount.html(null);
                                item.price.percent.text(null);
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
                            }

                            item.find('[data-basket-id]')
                                .data('add', item.quantity.get())
                                .data('basketQuantity', item.quantity.get());
                        };

                        item.update();

                        <?php if ($arResult['FORM']['SHOW']) { ?>
                            item.order.on('click', function () {
                                order(data);
                            });
                        <?php } ?>

                        item.quantity.on('change', function (event, value) {
                            var purchaseButton = item.find('[data-basket-id]');
                            item.update();

                            if (!(purchaseButton.attr('data-basket-state') === 'none' || purchaseButton.attr('data-basket-state') === 'buy')) {
                                purchaseButton.attr('data-basket-state', 'update');
                            }
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

                                        if (!entity.available) {
                                            item.toggle.close(200);
                                        } else {
                                            item.toggle.open(400);
                                        }
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

                    });
                };

                BX.message(<?= JavaScript::toObject([
                    'BTN_MESSAGE_LAZY_LOAD' => '',
                    'BTN_MESSAGE_LAZY_LOAD_WAITER' => ''
                ]) ?>);

                component = new JCCatalogSectionComponent(<?= JavaScript::toObject([
                    'siteId' => SITE_ID,
                    'componentPath' => $componentPath,
                    'navParams' => $arNavigation,
                    'deferredLoad' => false,
                    'initiallyShowHeader' => false,
                    'bigData' => $arResult['BIG_DATA'],
                    'lazyLoad' => $arVisual['NAVIGATION']['LAZY']['BUTTON'],
                    'loadOnScroll' => $arVisual['NAVIGATION']['LAZY']['SCROLL'],
                    'template' => $sSignedTemplate,
                    'parameters' => $sSignedParameters,
                    'ajaxId' => $arParams['AJAX_ID'],
                    'container' => $sTemplateContainer
                ]) ?>);

                component.processItems = (function () {
                    var action = component.processItems;

                    return function () {
                        var result = action.apply(this, arguments);

                        root.update();
                        universe.basket.update();

                        return result;
                    };
                })();

                root.update();
            });
        })(jQuery, intec);
    </script>
<?php

unset($sSignedParameters);
unset($sSignedTemplate);
unset($oSigner);