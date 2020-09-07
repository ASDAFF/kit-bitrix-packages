<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\component\InnerTemplate;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;
use intec\core\helpers\StringHelper;
use intec\core\helpers\Type;

/**
 * @var array $arParams
 * @var array $arResult
 * @var array $arData
 * @var InnerTemplate $this
 */

$bPartLeftShow = $arResult['SEARCH']['SHOW'] ||
    $arResult['EMAIL']['SHOW'] ||
    $arResult['PHONE']['SHOW'] ||
    $arResult['ADDRESS']['SHOW'] ||
    $arResult['CONTACTS']['USE'];

$bPartRightShow = $arResult['MENU']['MAIN']['SHOW'];
$bPartsShow =
    $bPartLeftShow ||
    $bPartRightShow;

$bParts2Show =
    $arResult['FORMS']['CALL']['SHOW'] ||
    $arResult['SEARCH']['SHOW'];

$bPanelShow =
    $arResult['COPYRIGHT']['SHOW'] ||
    $arResult['LOGOTYPE']['SHOW'] ||
    $arResult['SOCIAL']['SHOW'];

$bPhoneShow = false;
$bAddressShow = false;

if ($arResult['CONTACTS']['USE']) {
    foreach ($arResult['CONTACTS']['ITEMS'] as $arContact) {
        if (!empty($arContact['DATA']['PHONE']))
            $bPhoneShow = true;

        if (!empty($arContact['DATA']['ADDRESS']))
            $bAddressShow = true;
    }

    unset($arContact);
} else {
    $bPhoneShow = $arResult['PHONE']['SHOW'];
    $bAddressShow = $arResult['ADDRESS']['SHOW'];
}

?>
<div class="widget-view-6 intec-content-wrap">
    <div class="widget-wrapper intec-content">
        <div class="widget-wrapper-2 intec-content-wrapper">
            <?php if ($bPartsShow) { ?>
                <div class="<?= Html::cssClassFromArray([
                    'widget-parts',
                    'intec-grid' => [
                        '',
                        'nowrap',
                        'a-h-start',
                        'a-v-start',
                        '768-wrap'
                    ]
                ]) ?>">
                    <?php if ($bPartRightShow) { ?>
                        <div class="widget-part widget-part-left intec-grid-item-auto intec-grid-item-768-1">
                            <div class="widget-part-items">
                                <div class="widget-part-item widget-title">
                                    <?= Loc::getMessage('C_MAIN_FOOTER_TEMPLATE_1_VIEW_6_TITLE') ?>
                                </div>
                                <?php if ($arResult['EMAIL']['SHOW']) { ?>
                                    <div class="widget-part-item widget-email">
                                        <span class="widget-part-item-icon">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                        <a class="widget-part-item-text intec-cl-text" href="mailto:<?= $arResult['EMAIL']['VALUE'] ?>">
                                            <?= $arResult['EMAIL']['VALUE'] ?>
                                        </a>
                                    </div>
                                <?php } ?>
                                <?php if (!$arResult['CONTACTS']['USE'] && $bPhoneShow) { ?>
                                    <div class="widget-part-item widget-phone">
                                        <span class="widget-part-item-icon">
                                            <i class="fas fa-phone"></i>
                                        </span>
                                        <a class="widget-part-item-text intec-cl-text" href="tel:<?= $arResult['PHONE']['VALUE']['LINK'] ?>">
                                            <?= $arResult['PHONE']['VALUE']['DISPLAY'] ?>
                                        </a>
                                    </div>
                                <?php } ?>
                                <?php if (!$arResult['CONTACTS']['USE'] && $bAddressShow) { ?>
                                    <div class="widget-part-item widget-address">
                                        <span class="widget-part-item-icon">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </span>
                                        <span class="widget-part-item-text intec-cl-text">
                                            <?= $arResult['ADDRESS']['VALUE'] ?>
                                        </span>
                                    </div>
                                <?php } ?>
                                <?php if ($arResult['CONTACTS']['USE']) { ?>
                                    <div class="widget-part-item widget-contacts">
                                        <div class="widget-contacts-items">
                                            <?php foreach ($arResult['CONTACTS']['ITEMS'] as $arContact) { ?>
                                            <?php if (
                                                (empty($arContact['DATA']['PHONE'])) &&
                                                (empty($arContact['DATA']['ADDRESS']))
                                            ) continue ?>
                                                <div class="widget-contacts-item">
                                                    <div class="widget-contacts-item-name intec-cl-text">
                                                        <?= $arContact['NAME'] ?>
                                                    </div>
                                                    <?php if (!empty($arContact['DATA']['PHONE'])) { ?>
                                                        <div class="widget-contacts-item-phone">
                                                            <span><?= Loc::getMessage('C_MAIN_FOOTER_TEMPLATE_1_VIEW_6_CONTACTS_ITEM_PHONE_TITLE') ?>:</span>
                                                            <a href="tel:<?= $arContact['DATA']['PHONE']['LINK'] ?>">
                                                                <?= $arContact['DATA']['PHONE']['DISPLAY'] ?>
                                                            </a>
                                                        </div>
                                                    <?php } ?>
                                                    <?php if (!empty($arContact['DATA']['ADDRESS'])) { ?>
                                                        <div class="widget-contacts-item-address">
                                                            <span><?= Loc::getMessage('C_MAIN_FOOTER_TEMPLATE_1_VIEW_6_CONTACTS_ITEM_ADDRESS_TITLE') ?>:</span>
                                                            <span>
                                                                <?= $arContact['DATA']['ADDRESS'] ?>
                                                            </span>
                                                        </div>
                                                    <?php } ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="widget-part widget-part-right intec-grid-item intec-grid-item-768-1">
                        <?php if ($bPartLeftShow) { ?>
                            <?php include(__DIR__.'/../../parts/menu/main.columns.1.php') ?>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            <?php if ($bParts2Show) { ?>
                <div class="widget-parts-2 intec-grid intec-grid-650-wrap intec-grid-a-h-start intec-grid-a-v-center">
                    <?php if ($arResult['FORMS']['CALL']['SHOW']) { ?>
                        <div class="widget-part-2 widget-form intec-grid-item intec-grid-item-650-1">
                            <?= Html::tag('div', Loc::getMessage('C_MAIN_FOOTER_TEMPLATE_1_VIEW_6_FORMS_CALL_BUTTON'), [
                                'class' => [
                                    'widget-form-button',
                                    'intec-ui' => [
                                        '',
                                        'control-button',
                                        'mod-round-half',
                                        'mod-transparent',
                                        'scheme-current',
                                        'size-4'
                                    ]
                                ],
                                'data' => [
                                    'action' => 'forms.call.open'
                                ]
                            ]) ?>
                            <?php include(__DIR__.'/../../parts/forms/call.php') ?>
                        </div>
                    <?php } ?>
                    <?php if ($arResult['SEARCH']['SHOW']) { ?>
                        <div class="widget-part-2 widget-search intec-grid-item-auto intec-grid-item-650-1">
                            <?php
                                $arSearch = [
                                    'TEMPLATE' => 'input.3'
                                ];

                                include(__DIR__.'/../../parts/search.php');
                            ?>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
            <div id="bx-composite-banner"></div>
            <?php if ($bPanelShow) { ?>
                <div class="widget-panel">
                    <div class="<?= Html::cssClassFromArray([
                        'widget-panel-items',
                        'intec-grid' => [
                            '',
                            'nowrap',
                            'a-h-start',
                            'a-v-center',
                            '768-wrap'
                        ]
                    ]) ?>">
                        <?php if ($arResult['COPYRIGHT']['SHOW']) { ?>
                            <div class="widget-panel-item intec-grid-item intec-grid-item-768-1">
                                <div class="widget-copyright">
                                    <?= $arResult['COPYRIGHT']['VALUE'] ?>
                                </div>
                            </div>
                        <?php } else { ?>
                            <div class="widget-panel-item widget-panel-item-empty intec-grid-item intec-grid-item-768-1"></div>
                        <?php } ?>
                        <?php if ($arResult['LOGOTYPE']['SHOW']) { ?>
                            <div class="widget-panel-item intec-grid-item-auto intec-grid-item-768-1">
                                <div class="widget-logotype">
                                    <a href="/" class="widget-logotype-wrapper">
                                        <?php include(__DIR__.'/../../parts/logotype.php') ?>
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if ($arResult['SOCIAL']['SHOW']) { ?>
                            <div class="widget-panel-item intec-grid-item intec-grid-item-768-1">
                                <!--noindex-->
                                <div class="widget-social">
                                    <div class="widget-social-items">
                                        <?php foreach ($arResult['SOCIAL']['ITEMS'] as $arItem) { ?>
                                            <?php if (!$arItem['SHOW']) continue ?>
                                            <div class="widget-social-item">
                                                <a rel="nofollow" href="<?= $arItem['LINK'] ?>" class="widget-social-item-icon glyph-icon-<?= StringHelper::toLowerCase($arItem['CODE']) ?>"></a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <!--/noindex-->
                            </div>
                        <?php } else { ?>
                            <div class="widget-panel-item widget-panel-item-empty intec-grid-item intec-grid-item-768-1"></div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>