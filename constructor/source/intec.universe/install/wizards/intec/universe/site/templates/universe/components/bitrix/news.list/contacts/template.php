<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;
use intec\constructor\models\Build;
use Bitrix\Main\Loader;

/**
 * @var array $arParams
 * @var array $arResult
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @global CDatabase $DB
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $templateFile
 * @var string $templateFolder
 * @var string $componentPath
 * @var CBitrixComponent $component
 */

Loc::loadMessages(__FILE__);

$this->setFrameMode(true);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arVisual = $arResult['VISUAL'];

if (!Loader::IncludeModule('intec.core'))
    return;

?>
<div class="contacts" id="<?= $sTemplateId ?>">
    <?php if ($arResult['MAP']['SHOW']) { ?>
        <div class="contacts-map" id="<?= $sTemplateId ?>_map">
        <?php
            $arData = [];

            if (!empty($arResult['CONTACT']) && !empty($arResult['CONTACT']['DATA']['MAP'])) {
                if ($arResult['MAP']['VENDOR'] == 'google') {
                    $arData['google_lat'] = $arResult['CONTACT']['DATA']['MAP']['LATITUDE'];
                    $arData['google_lon'] = $arResult['CONTACT']['DATA']['MAP']['LONGITUDE'];
                    $arData['google_scale'] = 16;
                } else {
                    $arData['yandex_lat'] = $arResult['CONTACT']['DATA']['MAP']['LATITUDE'];
                    $arData['yandex_lon'] = $arResult['CONTACT']['DATA']['MAP']['LONGITUDE'];
                    $arData['yandex_scale'] = 16;
                }
            }

            $arData['PLACEMARKS'] = [];

            foreach ($arResult['ITEMS'] as $arItem) {
                if (empty($arItem['DATA']['MAP']))
                    continue;

                $arData['PLACEMARKS'][] = [
                    'TEXT' => $arItem['NAME'],
                    'LAT' => $arItem['DATA']['MAP']['LATITUDE'],
                    'LON' => $arItem['DATA']['MAP']['LONGITUDE']
                ];
            }

        ?>
            <?php if ($arResult['MAP']['VENDOR'] === 'google') { ?>
                <?php $APPLICATION->IncludeComponent(
                    'bitrix:map.google.view',
                    '.default', [
                        'MAP_ID' => $arResult['MAP']['ID'],
                        'API_KEY' => $arParams['API_KEY_MAP'],
                        'INIT_MAP_TYPE' => 'ROADMAP',
                        'MAP_DATA' => serialize($arData),
                        'MAP_WIDTH' => '100%',
                        'MAP_HEIGHT' => '100%',
                        'OVERLAY' => 'Y',
                        'CONTROLS' => [
                            'SMALL_ZOOM_CONTROL',
                            'TYPECONTROL',
                            'SCALELINE'
                        ],
                        'OPTIONS' => [
                            'ENABLE_SCROLL_ZOOM',
                            'ENABLE_DBLCLICK_ZOOM',
                            'ENABLE_DRAGGING',
                            'ENABLE_KEYBOARD'
                        ]
                    ],
                    $component,
                    ['HIDE_ICONS' => 'Y']
                ) ?>
            <?php } else { ?>
                <?php $APPLICATION->IncludeComponent(
                    'bitrix:map.yandex.view',
                    '.default', [
                        'INIT_MAP_TYPE' => 'ROADMAP',
                        'MAP_ID' => $arResult['MAP']['ID'],
                        'MAP_DATA' => serialize($arData),
                        'MAP_WIDTH' => '100%',
                        'MAP_HEIGHT' => '100%',
                        'OVERLAY' => 'Y',
                        'CONTROLS' => array(
                            0 => 'ZOOM',
                            1 => 'MINIMAP',
                            2 => 'TYPECONTROL',
                            3 => 'SCALELINE'
                        ),
                        'OPTIONS' => array(
                            0 => 'ENABLE_SCROLL_ZOOM',
                            1 => 'ENABLE_DBLCLICK_ZOOM',
                            2 => 'ENABLE_DRAGGING'
                        )
                    ],
                    $component,
                    ['HIDE_ICONS' => 'Y']
                ) ?>
            <?php } ?>
        </div>
    <?php } ?>
    <div class="intec-content contacts-contact-wrap">
        <div class="intec-content-wrapper">
            <?php if (!empty($arResult['CONTACT'])) { ?>
                <?php if (
                    !empty($arResult['CONTACT']['DATA']['ADDRESS']) ||
                    !empty($arResult['CONTACT']['DATA']['PHONE']) ||
                    !empty($arResult['CONTACT']['DATA']['WORK_TIME']) ||
                    !empty($arResult['CONTACT']['DATA']['EMAIL'])
                ) { ?>
                    <?= Html::beginTag('div', [
                        'class' => Html::cssClassFromArray([
                            'contacts-contact' => true,
                            'contacts-contact-with-map' => $arResult['MAP']['SHOW']
                        ], true)
                    ]) ?>
                        <div class="contacts-contact-wrapper">
                            <?php if (!empty($arResult['CONTACT']['DATA']['ADDRESS'])) { ?>
                                <div class="contacts-contact-parameter">
                                    <div class="contacts-contact-parameter-wrapper">
                                        <div class="contacts-contact-icon-wrap">
                                            <div class="intec-aligner"></div>
                                            <div class="contacts-contact-icon" style="background-image: url('<?= $this->GetFolder().'/images/location.png' ?>')"></div>
                                        </div>
                                        <div class="contacts-contact-text-wrap">
                                            <div class="intec-aligner"></div>
                                            <div class="contacts-contact-text">
                                                <?php if (!empty($arResult['CONTACT']['DATA']['CITY'])) { ?>
                                                    <div class="contacts-contact-title">
                                                        <?= $arResult['CONTACT']['DATA']['CITY'] ?>
                                                    </div>
                                                <?php } ?>
                                                <?= $arResult['CONTACT']['DATA']['ADDRESS'] ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (!empty($arResult['CONTACT']['DATA']['PHONE'])) { ?>
                                <div class="contacts-contact-parameter">
                                    <div class="contacts-contact-parameter-wrapper">
                                        <div class="contacts-contact-icon-wrap">
                                            <div class="intec-aligner"></div>
                                            <div class="contacts-contact-icon" style="background-image: url('<?= $this->GetFolder().'/images/phone.png' ?>')"></div>
                                        </div>
                                        <div class="contacts-contact-text-wrap">
                                            <div class="intec-aligner"></div>
                                            <div class="contacts-contact-text">
                                                <div class="contacts-contact-title">
                                                    <?= Loc::getMessage('C_NEWS_LIST_CONTACTS_PROPERTY_PHONE') ?>:
                                                </div>
                                                <a class="contacts-contact-value" href="<?= 'tel:'.$arResult['CONTACT']['DATA']['PHONE']['VALUE'] ?>">
                                                    <?= $arResult['CONTACT']['DATA']['PHONE']['DISPLAY'] ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (!empty($arResult['CONTACT']['DATA']['WORK_TIME'])) { ?>
                                <div class="contacts-contact-parameter">
                                    <div class="contacts-contact-parameter-wrapper">
                                        <div class="contacts-contact-icon-wrap">
                                            <div class="intec-aligner"></div>
                                            <div class="contacts-contact-icon" style="background-image: url('<?= $this->GetFolder().'/images/time.png' ?>')"></div>
                                        </div>
                                        <div class="contacts-contact-text-wrap">
                                            <div class="intec-aligner"></div>
                                            <div class="contacts-contact-text">
                                                <div class="contacts-contact-title">
                                                    <?= Loc::getMessage('C_NEWS_LIST_CONTACTS_PROPERTY_WORK_TIME') ?>:
                                                </div>
                                                <div class="contacts-contact-value">
                                                    <?php foreach ($arResult['CONTACT']['DATA']['WORK_TIME'] as $arValue) { ?>
                                                        <div class="contacts-contact-value-part">
                                                            <?= !empty($arValue['RANGE']) ? $arValue['RANGE'].' '.$arValue['TIME'] : $arValue['TIME'] ?>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (!empty($arResult['CONTACT']['DATA']['EMAIL'])) { ?>
                                <div class="contacts-contact-parameter">
                                    <div class="contacts-contact-parameter-wrapper">
                                        <div class="contacts-contact-icon-wrap">
                                            <div class="intec-aligner"></div>
                                            <div class="contacts-contact-icon" style="background-image: url('<?= $this->GetFolder().'/images/email.png' ?>')"></div>
                                        </div>
                                        <div class="contacts-contact-text-wrap">
                                            <div class="intec-aligner"></div>
                                            <div class="contacts-contact-text">
                                                <div class="contacts-contact-title">
                                                    <?= Loc::getMessage('C_NEWS_LIST_CONTACTS_PROPERTY_EMAIL') ?>:
                                                </div>
                                                <a class="contacts-contact-value" href="<?= 'mailto:'.$arResult['CONTACT']['DATA']['EMAIL'] ?>">
                                                    <?= $arResult['CONTACT']['DATA']['EMAIL'] ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?= Html::endTag('div') ?>
                    <?php include(__DIR__.'/parts/schema.php') ?>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <?php if ($arParams['SHOW_LIST'] == 'SHOPS') { ?>
                <?php require('parts/shops.php') ?>
            <?php } ?>
            <?php if ($arParams['SHOW_LIST'] == 'OFFICES') { ?>
                <?php require('parts/offices.php') ?>
            <?php } ?>
            <?php if ($arResult['MAP']['SHOW']) { ?>
                <script type="text/javascript">
                    (function ($, api) {
                        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
                        var buttons = $('[data-latitude][data-longitude]', root);
                        var initialize;
                        var loader;
                        var move;
                        var map;

                        initialize = function () {
                            if (!api.isObject(window.maps))
                                return false;

                            map = window.maps[<?= JavaScript::toObject($arResult['MAP']['ID']) ?>];

                            if (map == null)
                                return false;

                            buttons.on('click', function (event) {
                                var item = $(this);
                                var anchor = item.attr('href');

                                move(
                                    item.data('latitude'),
                                    item.data('longitude')
                                );

                                if (anchor) {
                                    $(window).stop().animate({
                                        scrollTop: $(anchor).offset().top
                                    }, 1000);

                                    event.preventDefault();
                                }
                            });

                            return true;
                        };

                        move = function (latitude, longitude) {
                            latitude = api.toFloat(latitude);
                            longitude = api.toFloat(longitude);

                            <?php if ($arResult['MAP']['VENDOR'] == 'google') { ?>
                                map.panTo(new google.maps.LatLng(latitude, longitude));
                            <?php } else { ?>
                                map.panTo([latitude, longitude]);
                            <?php } ?>
                        };

                        <?php if ($arResult['MAP']['VENDOR'] === 'google') { ?>
                            BX.ready(initialize);
                        <?php } else { ?>
                            loader = function () {
                                var load;

                                load = function () {
                                    if (!initialize())
                                        setTimeout(load, 100);
                                };

                                if (window.ymaps) {
                                    ymaps.ready(load);
                                } else {
                                    setTimeout(loader, 100);
                                }
                            };

                            loader();
                        <?php } ?>
                    })(jQuery, intec)
                </script>
            <?php } ?>
            <?php if ($arParams['SHOW_FORM'] == 'Y') { ?>
                <!--noindex-->
                <div class="contacts-form-wrap">
                    <?php if (Loader::IncludeModule('form')) {?>
                        <?php $APPLICATION->IncludeComponent(
                            'bitrix:form.result.new',
                            'contacts',
                            array(
                                'WEB_FORM_ID' => $arParams['WEB_FORM_ID'],
                                'AJAX_MODE' => 'Y',
                                'IGNORE_CUSTOM_TEMPLATE' => 'N',
                                'USE_EXTENDED_ERRORS' => 'N',
                                'LIST_URL' => null,
                                'EDIT_URL' => null,
                                'SUCCESS_URL' => null,
                                'CHAIN_ITEM_TEXT' => null,
                                'CHAIN_ITEM_LINK' => null,
                                'CONSENT_URL' => $arResult['URL']['CONSENT']
                            ),
                            $component,
                            array(
                                'HIDE_ICONS' => 'Y'
                            )
                        ) ?>
                    <?php } else if (Loader::IncludeModule('intec.startshop')) {?>
                        <?php $APPLICATION->IncludeComponent(
                            "intec:startshop.forms.result.new",
                            "contacts",
                            array(
                                "COMPONENT_TEMPLATE" => "contacts",
                                "FORM_ID" => $arParams['WEB_FORM_ID'],
                                "AJAX_MODE" => "Y",
                                "CONSENT_URL" => $arResult['URL']['CONSENT'],
                                "AJAX_OPTION_JUMP" => "N",
                                "AJAX_OPTION_STYLE" => "Y",
                                "AJAX_OPTION_HISTORY" => "N",
                                "AJAX_OPTION_ADDITIONAL" => "",
                                "REQUEST_VARIABLE_ACTION" => "action",
                                "FORM_VARIABLE_CAPTCHA_SID" => "CAPTCHA_SID",
                                "FORM_VARIABLE_CAPTCHA_CODE" => "CAPTCHA_CODE"
                            ),
                            $component,
                            array(
                                'HIDE_ICONS' => 'Y'
                            )
                        ); ?>
                    <?php } ?>
                </div>
                <!--/noindex-->
            <?php } ?>
        </div>
    </div>
</div>
