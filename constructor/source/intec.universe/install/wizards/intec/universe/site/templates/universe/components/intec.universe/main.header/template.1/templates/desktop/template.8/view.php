<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\component\InnerTemplate;
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

foreach ([
    'LOGOTYPE',
    'CONTACTS',
    'EMAIL',
    'BASKET',
    'DELAY',
    'COMPARE'
] as $sBlock) {
    $bContainerShow = $bContainerShow || $arResult[$sBlock]['SHOW']['DESKTOP'];
}

if ($arResult['MENU']['MAIN']['SHOW']['DESKTOP'])
    $bContainerShow = true;

if ($arResult['MENU']['INFO']['SHOW'])
    $bContainerShow = true;

$bBasketShow =
    $arResult['BASKET']['SHOW']['DESKTOP'] ||
    $arResult['DELAY']['SHOW']['DESKTOP'] ||
    $arResult['COMPARE']['SHOW']['DESKTOP'];

?>
<div class="widget-view-desktop-8">
    <?php if ($bContainerShow) { ?>
        <?php if ($arResult['WIDE_HEADER']) { ?>
        <div class="intec-content intec-content-visible intec-content-primary">
            <div class="intec-content-wrapper">
        <?php } ?>
               <div class="widget-wrapper">
                    <?= Html::beginTag('div', [
                        'class' => [
                            'widget-wrapper-2',
                            'intec-grid' => [
                                '',
                                'nowrap',
                                'a-v-center',
                                'i-h-18'
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
                        <div class="widget-menu-wrap-2 intec-grid-item">
                            <div class="widget-menu-wrap-1 intec-grid intec-grid-nowrap intec-grid-a-v-center intec-grid-i-h-18">
                                <?php if ($arResult['MENU']['INFO']['SHOW']) { ?>
                                    <div class="widget-menu-wrap intec-grid-item intec-grid-item-a-stretch intec-grid-item-shrink-1">
                                        <div class="widget-item widget-menu">
                                            <?php include(__DIR__.'/parts/menu/main.horizontal.2.php') ?>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="intec-grid-item"></div>
                                <?php } ?>
                                <?php if ($arResult['REGIONALITY']['USE']) { ?>
                                   <div class="widget-region-wrap intec-grid-item-auto">
                                       <!--noindex-->
                                       <div class="widget-region">
                                           <div class="widget-region-icon intec-cl-text">
                                               <i class="fas fa-map-marker-alt"></i>
                                           </div>
                                           <div class="widget-region-text">
                                               <?php $APPLICATION->IncludeComponent('intec.regionality:regions.select', '', []) ?>
                                           </div>
                                       </div>
                                       <!--/noindex-->
                                   </div>
                                <?php } ?>
                                <?php if ($arResult['CONTACTS']['SHOW']['DESKTOP']) { ?>
                                    <?php $arContact = $arResult['CONTACTS']['SELECTED'] ?>
                                    <?php $arContacts = $arResult['CONTACTS']['VALUES'] ?>
                                    <div class="widget-contacts-wrap intec-grid-item-auto">
                                        <?= Html::beginTag('div', [
                                            'class' => [
                                                'widget-item',
                                                'widget-contacts'
                                            ],
                                            'data' => [
                                                'multiple' => !empty($arContacts) ? 'true' : 'false',
                                                'expanded' => 'false',
                                                'block' => 'phone'
                                            ]
                                        ]) ?>
                                            <div class="widget-phone">
                                                <div class="widget-phone-content">
                                                    <?php if ($arResult['CONTACTS']['ADVANCED']) { ?>
                                                        <a href="tel:<?= $arContact['PHONE']['VALUE'] ?>" class="widget-phone-text intec-cl-text-hover" data-block-action="popup.open">
                                                            <?= $arContact['PHONE']['DISPLAY'] ?>
                                                        </a>
                                                    <?php } else { ?>
                                                        <a href="tel:<?= $arContact['VALUE'] ?>" class="widget-phone-text intec-cl-text-hover" data-block-action="popup.open">
                                                            <?= $arContact['DISPLAY'] ?>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if (!empty($arContacts)) { ?>
                                                        <div class="widget-phone-popup" data-block-element="popup">
                                                            <div class="widget-phone-popup-wrapper" data-advanced="<?= $arResult['CONTACTS']['ADVANCED'] ? 'true' : 'false' ?>">
                                                                <?php if ($arResult['CONTACTS']['ADVANCED']) { ?>
                                                                    <?php foreach ($arContacts as $arContact) { ?>
                                                                        <div class="widget-phone-popup-contacts">
                                                                            <?php if (!empty($arContact['PHONE'])) { ?>
                                                                                <a href="tel:<?= $arContact['PHONE']['VALUE'] ?>" class="widget-phone-popup-contact phone intec-cl-text-hover">
                                                                                    <?= $arContact['PHONE']['DISPLAY'] ?>
                                                                                </a>
                                                                            <?php } ?>
                                                                            <?php if (!empty($arContact['ADDRESS'])) { ?>
                                                                                <div class="widget-phone-popup-contact address">
                                                                                    <?= $arContact['ADDRESS'] ?>
                                                                                </div>
                                                                            <?php } ?>
                                                                            <?php if (!empty($arContact['SCHEDULE'])) { ?>
                                                                                <div class="widget-phone-popup-contact schedule">
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
                                                                                <a href="mailto:<?= $arContact['EMAIL'] ?>" class="widget-phone-popup-contact email intec-cl-text-hover">
                                                                                    <?= $arContact['EMAIL'] ?>
                                                                                </a>
                                                                            <?php } ?>
                                                                        </div>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <?php foreach ($arContacts as $arContact) { ?>
                                                                        <a href="tel:<?= $arContact['VALUE'] ?>" class="widget-phone-popup-item intec-cl-text-hover">
                                                                            <?= $arContact['DISPLAY'] ?>
                                                                        </a>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                                <?php if (!empty($arContacts)) { ?>
                                                    <div class="widget-phone-arrow far fa-chevron-down" data-block-action="popup.open"></div>
                                                <?php } ?>
                                            </div>
                                            <?php if ($arResult['EMAIL']['SHOW']['DESKTOP']) { ?>
                                                <div class="widget-email-wrap">
                                                    <div class="widget-email">
                                                        <a href="mailto:<?= $arResult['EMAIL']['VALUE'] ?>" class="widget-email-text intec-cl-text-hover">
                                                            <?= $arResult['EMAIL']['VALUE'] ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        <?= Html::endTag('div') ?>
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
                                    <div class="widget-button-wrap intec-grid-item-auto">
                                        <?= Html::tag('div', Loc::getMessage('C_HEADER_TEMP1_DESKTOP_TEMP8_BUTTON'), [
                                            'class' => [
                                                'widget-button',
                                                'intec-ui' => [
                                                    '',
                                                    'control-button',
                                                    'scheme-current'
                                                ]
                                            ],
                                            'data-action' => "forms.call.open"
                                        ]) ?>
                                        <?php include(__DIR__.'/../../../parts/forms/call.php') ?>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                       <?php if ($arResult['AUTHORIZATION']['SHOW']['DESKTOP']) { ?>
                           <div class="widget-authorization-wrap intec-grid-item-auto">
                               <div class="widget-authorization widget-item">
                                   <?php include(__DIR__.'/../../../parts/auth/panel.2.php') ?>
                               </div>
                           </div>
                       <?php } ?>
                        <?php if ($bBasketShow) { ?>
                            <div class="widget-basket-wrap intec-grid-item-auto">
                                <div class="widget-basket widget-item">
                                    <?php include(__DIR__.'/../../../parts/basket.php') ?>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($arResult['MENU']['MAIN']['SHOW']['DESKTOP']) { ?>
                            <div class="widget-menu-wrap intec-grid-item-auto">
                                <div class="widget-item widget-menu">
                                    <?php $arMenuParams = ['TRANSPARENT' => 'Y'] ?>
                                    <?php include(__DIR__.'/../../../parts/menu/main.popup.php') ?>
                                </div>
                            </div>
                        <?php } ?>
                   <?= Html::endTag('div') ?>
                </div>
        <?php if ($arResult['WIDE_HEADER']) { ?>
            </div>
        </div>
        <?php } ?>
    <?php } ?>
</div>