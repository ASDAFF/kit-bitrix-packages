<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\JavaScript;

/**
 * @var array $arParams
 * @var array $arResult
 * @var IntecSaleOrderFastComponent $component
 * @var CBitrixComponentTemplate $this
 */

$this->setFrameMode(false);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this, true));
$arProduct = $arResult['PRODUCT'];
$arVisual = $arResult['VISUAL'];

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-intec-universe',
        'c-sale-order-fast',
        'c-sale-order-fast-default'
    ],
    'data' => [
        'product' => !empty($arProduct) ? 'true' : 'false'
    ]
]) ?>
    <?php if (!empty($arResult['ERROR']) && $arResult['ERROR']['CODE'] === 'SETUP') { ?>
        <div class="sale-order-fast-error">
            <?= $arResult['ERROR']['MESSAGE'] ?>
        </div>
    <?php } else { ?>
        <div class="sale-order-fast-parts intec-grid intec-grid-wrap intec-grid-a-v-stretch">
            <?php if (!empty($arProduct)) { ?>
            <?php
                $sPicture = null;

                if (!empty($arProduct['PREVIEW_PICTURE'])) {
                    $sPicture = $arProduct['PREVIEW_PICTURE'];
                } else if (!empty($arProduct['DETAIL_PICTURE'])) {
                    $sPicture = $arProduct['DETAIL_PICTURE'];
                }

                if (!empty($sPicture)) {
                    $sPicture = CFile::ResizeImageGet($sPicture, [
                        'width' => 280,
                        'height' => 280
                    ], BX_RESIZE_IMAGE_PROPORTIONAL);

                    if (!empty($sPicture))
                        $sPicture = $sPicture['src'];
                }

                if (empty($sPicture))
                    $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';
            ?>
                <div class="sale-order-fast-part sale-order-fast-part-product intec-grid-item-auto">
                    <div class="sale-order-fast-product">
                        <div class="sale-order-fast-product-name">
                            <?= $arProduct['NAME'] ?>
                        </div>
                        <div class="sale-order-fast-product-quantity">
                            <?php if ($arResult['AVAILABLE']) { ?>
                                <?= Loc::getMessage('C_SALE_ORDER_FAST_DEFAULT_QUANTITY').':' ?>
                                <?= $arResult['QUANTITY'] ?>
                                <?php if (!empty($arProduct['MEASURE']['VALUE'])) { ?>
                                    <?= $arProduct['MEASURE']['VALUE'].'.' ?>
                                <?php } ?>
                            <?php } else { ?>
                                <?= Loc::getMessage('C_SALE_ORDER_FAST_DEFAULT_UNAVAILABLE') ?>
                            <?php } ?>
                        </div>
                        <div class="sale-order-fast-product-picture intec-image intec-image-effect">
                            <div class="intec-aligner"></div>
                            <?= Html::img($arVisual['LAZYLOAD']['USE'] ? $arVisual['LAZYLOAD']['STUB'] : $sPicture, [
                                'class' => 'widget-element-picture',
                                'loading' => 'lazy',
                                'alt' => Html::encode($arProduct['NAME']),
                                'title' => Html::encode($arProduct['NAME']),
                                'data' => [
                                    'lazyload-use' => $arVisual['LAZYLOAD']['USE'] ? 'true' : 'false',
                                    'original' => $arVisual['LAZYLOAD']['USE'] ? $sPicture : null
                                ]
                            ]) ?>
                        </div>
                        <?php if (!empty($arResult['PRICE'])) { ?>
                            <div class="sale-order-fast-product-price">
                                <div class="sale-order-fast-product-price-discount">
                                    <?= $arResult['PRICE']['DISCOUNT']['DISPLAY'] ?>
                                </div>
                                <?php if ($arResult['PRICE']['DISCOUNT']['PERCENT'] > 0) { ?>
                                    <div class="sale-order-fast-product-price-base">
                                        <?= $arResult['PRICE']['BASE']['DISPLAY'] ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            <div class="sale-order-fast-part sale-order-fast-part-form intec-ui-form intec-grid-item">
                <form action="<?= $APPLICATION->GetCurPageParam() ?>" method="POST" class="sale-order-fast-form">
                    <?= Html::hiddenInput($arResult['VARIABLES']['ACTION'], 'apply') ?>
                    <?= Html::hiddenInput($arResult['VARIABLES']['SESSION'], bitrix_sessid()) ?>
                    <div class="sale-order-fast-title">
                        <?= $arResult['MESSAGES']['TITLE'] ?>
                    </div>
                    <?php if (empty($arResult['ORDER'])) { ?>
                        <?php if (!empty($arResult['ERROR']) && $arResult['ERROR']['CODE'] === 'PROCESS' && $arResult['ERROR']['STEP'] !== 'PROPERTIES') { ?>
                            <div class="sale-order-fast-error">
                                <?= $arResult['ERROR']['MESSAGE'] ?>
                            </div>
                        <?php } ?>
                        <?php if (!empty($arResult['PROPERTIES'])) { ?>
                            <div class="sale-order-fast-fields intec-ui-form-fields">
                                <?php foreach ($arResult['PROPERTIES'] as $arProperty) { ?>
                                <?php
                                    $bRequired = $arProperty['REQUIRED'] === 'Y';
                                    $sName = $arProperty['NAME'];
                                    $sInput = $arResult['VARIABLES']['VALUES'].'['.$arProperty['ID'].']';
                                    $sType = $arProperty['TYPE'];
                                    $sValue = $arProperty['VALUE'];

                                    if ($arProperty['TYPE'] === 'LOCATION')
                                        continue;
                                ?>
                                    <?= Html::beginTag('div', [
                                        'class' => Html::cssClassFromArray([
                                            'sale-order-fast-field' => true,
                                            'intec-ui-form-field' => true,
                                            'intec-ui-form-field-required' => $bRequired
                                        ], true),
                                        'data' => [
                                            'error' => !empty($arProperty['ERROR']) ? 'true' : 'false'
                                        ]
                                    ]) ?>
                                        <div class="sale-order-fast-field-title intec-ui-form-field-title">
                                            <?= $sName ?>
                                        </div>
                                        <?php if (!empty($arProperty['ERROR'])) { ?>
                                            <div class="sale-order-fast-field-error">
                                                <?= $arProperty['ERROR']['MESSAGE'] ?>
                                            </div>
                                        <?php } ?>
                                        <div class="sale-order-fast-field-content intec-ui-form-field-content">
                                            <?php if ($arProperty['TYPE'] === 'STRING' || $arProperty['TYPE'] === 'NUMBER') { ?>
                                                <?= Html::textInput($sInput, $sValue, [
                                                    'class' => 'intec-ui intec-ui-control-input intec-ui-mod-block intec-ui-mod-round-3 intec-ui-size-2'
                                                ]) ?>
                                            <?php } else if ($arProperty['TYPE'] === 'Y/N') { ?>
                                                <?= Html::hiddenInput($sInput, 'N') ?>
                                                <label class="intec-ui intec-ui-control-checkbox intec-ui-scheme-current">
                                                    <?= Html::checkbox($sInput, $sValue === 'Y', [
                                                        'value' => 'Y'
                                                    ]) ?>
                                                    <span class="intec-ui-part-selector"></span>
                                                </label>
                                            <?php } ?>
                                        </div>
                                    <?= Html::endTag('div') ?>
                                <?php } ?>
                                <?php if ($arResult['FIELDS']['COMMENT']['USE']) { ?>
                                    <div class="sale-order-fast-field sale-order-fast-field-comment intec-ui-form-field">
                                        <div class="sale-order-fast-field-title intec-ui-form-field-title">
                                            <?= Loc::getMessage('C_SALE_ORDER_FAST_DEFAULT_FIELDS_COMMENT') ?>:
                                        </div>
                                        <div class="sale-order-fast-field-content intec-ui-form-field-content">
                                            <?= Html::textarea($arResult['VARIABLES']['VALUES'].'[COMMENT]', $arResult['FIELDS']['COMMENT']['VALUE'], [
                                                'class' => 'intec-ui intec-ui-control-input intec-ui-mod-block intec-ui-mod-round-3 intec-ui-size-2'
                                            ]) ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <div class="sale-order-fast-footer">
                            <?php if ($arResult['AVAILABLE']) { ?>
                                <?php if ($arResult['CONSENT']['SHOW']) { ?>
                                    <div class="sale-order-fast-footer-consent">
                                        <label class="intec-ui intec-ui-control-checkbox intec-ui-scheme-current">
                                            <input type="checkbox" onchange="this.checked = !this.checked" checked="checked">
                                            <span class="intec-ui-part-selector"></span>
                                            <span class="intec-ui-part-content">
                                                <?= Loc::getMessage('C_SALE_ORDER_FAST_DEFAULT_FOOTER_CONSENT_TEXT', [
                                                    '#URL#' => $arResult['CONSENT']['URL']
                                                ]) ?>
                                            </span>
                                        </label>
                                    </div>
                                <?php } ?>
                                <div class="sale-order-fast-footer-parts intec-grid intec-grid-nowrap intec-grid-500-wrap intec-grid-i-h-10 intec-grid-a-v-center">
                                    <div class="sale-order-fast-footer-part intec-grid-item-auto intec-grid-item-500-1">
                                        <input class="intec-ui intec-ui-control-button intec-ui-mod-round-3 intec-ui-scheme-current intec-ui-size-2" type="submit" value="<?= $arResult['MESSAGES']['BUTTON'] ?>" />
                                    </div>
                                    <div class="sale-order-fast-footer-part intec-grid-item intec-grid-item-shrink-1 intec-grid-item-500-1">
                                        <div class="sale-order-fast-footer-message">
                                            <?= Loc::getMessage('C_SALE_ORDER_FAST_DEFAULT_FOOTER_MESSAGE') ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="sale-order-fast-footer-message">
                                    <?= Loc::getMessage('C_SALE_ORDER_FAST_DEFAULT_MESSAGES_UNAVAILABLE') ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } else { ?>
                        <div class="sale-order-fast-message">
                            <?= $arResult['MESSAGES']['ORDER'] ?>
                        </div>
                    <?php } ?>
                </form>
            </div>
        </div>
    <?php } ?>
<?= Html::endTag('div') ?>