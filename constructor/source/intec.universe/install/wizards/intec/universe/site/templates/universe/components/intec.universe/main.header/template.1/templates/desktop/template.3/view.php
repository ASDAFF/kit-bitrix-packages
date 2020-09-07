<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\component\InnerTemplate;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arData
 * @var InnerTemplate $this
 */

$sTemplateId = $arData['id'];
$sTemplateType = $arData['type'];

$bContainerShow = false;
$bBasketShow =
    $arResult['BASKET']['SHOW']['DESKTOP'] ||
    $arResult['DELAY']['SHOW']['DESKTOP'] ||
    $arResult['COMPARE']['SHOW']['DESKTOP'];

$arMenuMain = $arResult['MENU']['MAIN'];

foreach ([
    'LOGOTYPE',
    'CONTACTS',
    'AUTHORIZATION',
    'ADDRESS',
    'EMAIL',
    'SEARCH',
    'BASKET',
    'DELAY',
    'COMPARE'
] as $sBlock) {
    $bContainerShow = $bContainerShow || $arResult[$sBlock]['SHOW']['DESKTOP'];
}

?>
<div class="widget-view-desktop-3">
    <?php if ($bContainerShow) { ?>
        <div class="widget-container">
            <div class="intec-content intec-content-visible intec-content-primary">
                <div class="intec-content-wrapper">
                    <?= Html::beginTag('div', [
                        'class' => [
                            'widget-container-wrapper',
                            'intec-grid' => [
                                '',
                                'nowrap',
                                'a-h-start',
                                'a-v-center',
                                'i-h-15'
                            ]
                        ]
                    ]) ?>
                        <?php if ($arResult['LOGOTYPE']['SHOW']['DESKTOP']) { ?>
                            <div class="widget-logotype-wrap intec-grid-item-auto">
                                <?= Html::beginTag($arResult['LOGOTYPE']['LINK']['USE'] ? 'a' : 'div', [
                                    'href' => $arResult['LOGOTYPE']['LINK']['USE'] ? $arResult['LOGOTYPE']['LINK']['VALUE'] : null,
                                    'class' => [
                                        'widget-item',
                                        'widget-logotype',
                                        'intec-image'
                                    ]
                                ]) ?>
                                    <div class="intec-aligner"></div>
                                    <?php include(__DIR__.'/../../../parts/logotype.php') ?>
                                <?= Html::endTag($arResult['LOGOTYPE']['LINK']['USE'] ? 'a' : 'div') ?>
                            </div>
                        <?php } ?>
                        <?php if ($arResult['SEARCH']['SHOW']['DESKTOP']) { ?>
                            <div class="widget-search-wrap intec-grid-item-auto">
                                <div class="widget-search widget-item">
                                    <?php include(__DIR__.'/../../../parts/search/input.1.php') ?>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($arResult['ADDRESS']['SHOW']['DESKTOP'] || $arResult['REGIONALITY']['USE'] || $arResult['EMAIL']['SHOW']['DESKTOP']) { ?>
                            <div class="widget-contacts-wrap intec-grid-item-auto">
                                <div class="widget-contacts">
                                    <?php if ($arResult['REGIONALITY']['USE']) { ?>
                                        <div class="widget-region">
                                            <div class="widget-region-icon glyph-icon-location intec-cl-text"></div>
                                            <!--noindex-->
                                            <div class="widget-region-component">
                                                <?php $APPLICATION->IncludeComponent('intec.regionality:regions.select', '', []) ?>
                                            </div>
                                            <!--/noindex-->
                                        </div>
                                    <?php } else if ($arResult['ADDRESS']['SHOW']['DESKTOP']) { ?>
                                        <div class="widget-address">
                                            <div class="widget-address-icon glyph-icon-location intec-cl-text"></div>
                                            <div class="widget-address-text">
                                                <?= $arResult['ADDRESS']['VALUE'] ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if ($arResult['EMAIL']['SHOW']['DESKTOP']) { ?>
                                        <div class="widget-email">
                                            <div class="widget-email-icon glyph-icon-mail intec-cl-text"></div>
                                            <a href="mailto:<?= $arResult['EMAIL']['VALUE'] ?>" class="widget-email-text">
                                                <?= $arResult['EMAIL']['VALUE'] ?>
                                            </a>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="intec-grid-item"></div>
                        <?php if ($arResult['CONTACTS']['SHOW']['DESKTOP'] || $arResult['FORMS']['CALL']['SHOW']) { ?>
                            <div class="widget-phone-wrap intec-grid-item-auto">
                                <?php if ($arResult['CONTACTS']['SHOW']['DESKTOP']) { ?>
                                    <?php $arContact = $arResult['CONTACTS']['SELECTED'] ?>
                                    <?php $arContacts = $arResult['CONTACTS']['VALUES'] ?>
                                    <div class="widget-phone">
                                        <div class="widget-phone-content" data-block="phone" data-expanded="false">
                                            <div class="widget-phone-content-text intec-cl-text-hover" data-block-action="popup.open">
                                                <div class="widget-phone-content-text-icon intec-ui-icon intec-ui-icon-phone-1 intec-cl-text"></div>
                                                <?php if ($arResult['CONTACTS']['ADVANCED']) { ?>
                                                    <a href="tel:<?= $arContact['PHONE']['VALUE'] ?>" class="widget-phone-content-text-value intec-cl-text-hover">
                                                        <?= $arContact['PHONE']['DISPLAY'] ?>
                                                    </a>
                                                <?php } else { ?>
                                                    <a href="tel:<?= $arContact['VALUE'] ?>" class="widget-phone-content-text-value intec-cl-text-hover">
                                                        <?= $arContact['DISPLAY'] ?>
                                                    </a>
                                                <?php } ?>
                                            </div>
                                            <?php if (!empty($arContacts)) { ?>
                                                <div class="widget-phone-content-popup" data-block-element="popup">
                                                    <div class="widget-phone-content-popup-wrapper">
                                                        <?php if ($arResult['CONTACTS']['ADVANCED']) {?>
                                                            <?php foreach ($arContacts as $arContact) { ?>
                                                                <div class="widget-phone-content-popup-contacts">
                                                                    <?php if (!empty($arContact['PHONE'])) { ?>
                                                                        <a href="tel:<?= $arContact['PHONE']['VALUE'] ?>" class="widget-phone-content-popup-contact phone intec-cl-text-hover">
                                                                            <?= $arContact['PHONE']['DISPLAY'] ?>
                                                                        </a>
                                                                    <?php } ?>
                                                                    <?php if (!empty($arContact['ADDRESS'])) { ?>
                                                                        <div class="widget-phone-content-popup-contact address">
                                                                            <?= $arContact['ADDRESS'] ?>
                                                                        </div>
                                                                    <?php } ?>
                                                                    <?php if (!empty($arContact['SCHEDULE'])) { ?>
                                                                        <div class="widget-phone-content-popup-contact schedule">
                                                                            <?php if (Type::isArray($arContact['SCHEDULE'])) { ?>
                                                                                <?php foreach ($arContact['SCHEDULE'] as $sValue) { ?>
                                                                                    <span><?= $sValue ?></span>
                                                                                <?php } ?>
                                                                            <?php } else { ?>
                                                                                <?= $arContact['SCHEDULE'] ?>
                                                                            <?php } ?>
                                                                        </div>
                                                                    <?php } ?>
                                                                    <?php if (!empty($arContact['EMAIL'])) { ?>
                                                                        <a href="mailto:<?= $arContact['EMAIL'] ?>" class="widget-phone-content-popup-contact email intec-cl-text-hover">
                                                                            <?= $arContact['EMAIL'] ?>
                                                                        </a>
                                                                    <?php } ?>
                                                                </div>
                                                            <?php } ?>
                                                        <?php } else { ?>
                                                            <?php foreach ($arContacts as $arContact) { ?>
                                                                <a href="tel:<?= $arContact['VALUE'] ?>" class="widget-phone-content-popup-item intec-cl-text-hover">
                                                                    <?= $arContact['DISPLAY'] ?>
                                                                </a>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <?php if (!empty($arContacts) && !defined('EDITOR')) { ?>
                                            <script type="text/javascript">
                                                (function ($) {
                                                    $(document).on('ready', function () {
                                                        var root = $(<?= JavaScript::toObject('#'.$sTemplateId) ?>);
                                                        var block = $('[data-block="phone"]', root);
                                                        var popup = $('[data-block-element="popup"]', block);

                                                        popup.open = $('[data-block-action="popup.open"]', block);
                                                        popup.open.on('mouseenter', function () {
                                                            block.attr('data-expanded', 'true');
                                                        });

                                                        block.on('mouseleave', function () {
                                                            block.attr('data-expanded', 'false');
                                                        });
                                                    });
                                                })(jQuery)
                                            </script>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                                <?php if ($arResult['FORMS']['CALL']['SHOW']) { ?>
                                    <div class="widget-button-wrap">
                                        <div class="widget-button intec-cl-text" data-action="forms.call.open">
                                            <?= Loc::getMessage('C_HEADER_TEMP1_DESKTOP_TEMP3_BUTTON') ?>
                                        </div>
                                        <?php include(__DIR__.'/../../../parts/forms/call.php') ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                        <?php if ($bBasketShow) { ?>
                            <div class="widget-basket-wrap intec-grid-item-auto">
                                <div class="widget-basket widget-item">
                                    <?php include(__DIR__.'/../../../parts/basket.php') ?>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($arResult['AUTHORIZATION']['SHOW']['DESKTOP']) { ?>
                            <div class="widget-authorize-wrap intec-grid-item-auto">
                                <div class="widget-authorize widget-item">
                                    <?php include(__DIR__.'/../../../parts/auth/panel.php') ?>
                                </div>
                            </div>
                        <?php } ?>
                    <?= Html::endTag('div') ?>
                </div>
            </div>
        </div>
    <?php } ?>
    <?php if ($arMenuMain['SHOW']['DESKTOP']) { ?>
        <div class="widget-menu">
            <?php include(__DIR__.'/../../../parts/menu/main.horizontal.1.php') ?>
        </div>
    <?php } ?>
</div>
