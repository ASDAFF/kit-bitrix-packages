<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use Bitrix\Main\Localization\Loc;
use intec\core\helpers\JavaScript;
use intec\core\helpers\ArrayHelper;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

global $USER;

$this->setFrameMode(false);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$arUser = array();

if ($USER->IsAuthorized()) {
    $arUser = CUser::GetByID($USER->GetID())->Fetch();
}

?>
<?php $fPropertyDraw = function ($arProperty, $arUser = array()) {

    $sValue = '';

    if (isset($_REQUEST['PROPERTY_'.$arProperty['ID']])) {
        $sValue = $_REQUEST['PROPERTY_'.$arProperty['ID']];
    } else if (!empty($arProperty['USER_FIELD']) && !empty($arUser) && !empty($arUser[$arProperty['USER_FIELD']])) {
        $sValue = $arUser[$arProperty['USER_FIELD']];
    }

    if ($arProperty['TYPE'] == 'S' && empty($arProperty['SUBTYPE'])) { ?>
        <div class="intec-form-field">
            <label class="intec-form-label">
                <div class="intec-form-caption">
                    <?= $arProperty['LANG'][LANGUAGE_ID]['NAME'] ?>:
                    <?php if ($arProperty['REQUIRED'] == 'Y') { ?>
                        <span class="startshop-order-required">*</span>
                    <?php } ?>
                </div>
                <div class="intec-form-value">
                    <input type="text"
                           <?= $arProperty['DATA']['LENGTH'] > 0 ? ' maxlength="'. $arProperty['DATA']['LENGTH'] .'"' : '' ?>
                           class="intec-input"
                           name="PROPERTY_<?= $arProperty['ID'] ?>"
                           value="<?= htmlspecialcharsbx($sValue) ?>" />
                    <?php if (!empty($arProperty['LANG'][LANGUAGE_ID]['DESCRIPTION'])) { ?>
                        <div class="startshop-order-field-description"><?= $arProperty['LANG'][LANGUAGE_ID]['DESCRIPTION'] ?></div>
                    <?php } ?>
                </div>
            </label>
        </div>
    <?php } else if ($arProperty['TYPE'] == 'S' && $arProperty['SUBTYPE'] == 'TEXT') { ?>
        <div class="intec-form-field text">
            <label class="intec-form-label">
                <div class="intec-form-caption">
                    <?= $arProperty['LANG'][LANGUAGE_ID]['NAME'] ?>:
                    <?php if ($arProperty['REQUIRED'] == 'Y') { ?>
                        <span class="startshop-order-required">*</span>
                    <?php } ?>
                </div>
                <div class="intec-form-value">
                    <textarea name="PROPERTY_<?= $arProperty['ID'] ?>"
                              class="intec-input"><?= htmlspecialcharsbx($sValue) ?></textarea>
                    <?php if (!empty($arProperty['LANG'][LANGUAGE_ID]['DESCRIPTION'])) { ?>
                        <div class="startshop-order-field-description"><?= $arProperty['LANG'][LANGUAGE_ID]['DESCRIPTION'] ?></div>
                    <?php } ?>
                </div>
            </label>
        </div>
    <?php } else if ($arProperty['TYPE'] == 'B' && empty($arProperty['SUBTYPE'])) { ?>
        <div class="intec-form-field checkbox">
            <input type="hidden" value="N" name="PROPERTY_<?= $arProperty['ID'] ?>" />
            <label class="intec-form-label intec-ui intec-ui-control-switch intec-ui-scheme-current">
            <div class="intec-form-caption">
                <?= $arProperty['LANG'][LANGUAGE_ID]['NAME'] ?>:
            </div>
            <div class="intec-form-value">
                <input type="checkbox" value="Y" name="PROPERTY_<?= $arProperty['ID'] ?>"<?= $sValue == 'Y' ? ' checked="checked"' : '' ?> />
                <div class="intec-ui-part-selector"></div>
            </div>
                <?php if (!empty($arProperty['LANG'][LANGUAGE_ID]['DESCRIPTION'])) { ?>
                    <div class="startshop-order-field-description"><?= $arProperty['LANG'][LANGUAGE_ID]['DESCRIPTION'] ?></div>
                <?php } ?>
            </label>
        </div>
    <?php } else if ($arProperty['TYPE'] == 'L' && $arProperty['SUBTYPE'] == 'IBLOCK_ELEMENT') { ?>
        <div class="intec-form-field list">
            <label class="intec-form-label">
                <div class="intec-form-caption">
                    <?= $arProperty['LANG'][LANGUAGE_ID]['NAME'] ?>:
                    <?php if ($arProperty['REQUIRED'] == 'Y') { ?>
                        <span class="startshop-order-required">*</span>
                    <?php } ?>
                </div>
                <div class="intec-form-value">
                    <select name="PROPERTY_<?= $arProperty['ID'] ?>" class="intec-input">
                        <?php foreach ($arProperty['VALUES'] as $iPropertyKey => $arPropertyValue) { ?>
                            <option value="<?= $iPropertyKey ?>"
                                    <?= $sValue == $iPropertyKey ? 'selected="selected"' : '' ?>>
                                <?= htmlspecialcharsbx($arPropertyValue['NAME']) ?>
                            </option>
                        <?php } ?>
                    </select>
                    <?php if (!empty($arProperty['LANG'][LANGUAGE_ID]['DESCRIPTION'])) { ?>
                        <div class="startshop-order-field-description"><?= $arProperty['LANG'][LANGUAGE_ID]['DESCRIPTION'] ?></div>
                    <?php } ?>
                </div>
            </label>
        </div>
    <?php } ?>
<?php } ?>

<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-intec',
        'c-startshop-order',
        'c-startshop-order-default'
    ]
]) ?>
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <?php if (!empty($arResult['ERRORS'])) { ?>
                <div class="startshop-order-notifications">
                    <?php foreach ($arResult['ERRORS'] as $arError) { ?>
                        <?php if ($arError['CODE'] == 'DELIVERY_EMPTY') { ?>
                            <div class="intec-ui intec-ui-control-alert intec-ui-scheme-red intec-ui-m-b-20">
                                <div class="startshop-order-notification-wrapper">
                                    <?= Loc::getMessage('SO_DEFAULT_ERRORS_DELIVERY_EMPTY') ?>
                                </div>
                            </div>
                        <?php } elseif ($arError['CODE'] == 'PAYMENT_EMPTY') { ?>
                            <div class="intec-ui intec-ui-control-alert intec-ui-scheme-red intec-ui-m-b-20">
                                <div class="startshop-order-notification-wrapper">
                                    <?= Loc::getMessage('SO_DEFAULT_ERRORS_PAYMENT_EMPTY') ?>
                                </div>
                            </div>
                        <?php } elseif ($arError['CODE'] == 'PROPERTIES_EMPTY') { ?>
                            <div class="intec-ui intec-ui-control-alert intec-ui-scheme-red intec-ui-m-b-20">
                                <div class="startshop-order-notification-wrapper">
                                    <?php
                                        $arPropertiesEmpty = array();
                                        foreach ($arError['PROPERTIES'] as $arProperty) {
                                            $arPropertiesEmpty[] = $arProperty['LANG'][LANGUAGE_ID]['NAME'];
                                        }
                                    ?>
                                    <?= Loc::getMessage('SO_DEFAULT_ERRORS_PROPERTIES_EMPTY', array('#FIELDS#' => '<b>"'.implode('"</b>, <b>"', $arPropertiesEmpty).'"</b>')) ?>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
            <?php } ?>
            <?php if (!empty($arResult['ITEMS'])) { ?>
                <form method="POST" class="intec-form">
                    <input type="hidden" name="<?= $arParams['REQUEST_VARIABLE_ACTION'] ?>" value="order" />
                    <div class="startshop-order-content intec-grid intec-grid-1200-wrap intec-grid-i-h-15">
                        <div class="startshop-order-sections intec-grid-item intec-grid intec-grid-wrap">
                            <?php if (!empty($arResult['PROPERTIES'])) { ?>
                                <div class="startshop-order-section general intec-grid-item-1">
                                    <div class="startshop-order-section-title intec-grid intec-grid-a-v-center">
                                        <span class="startshop-order-section-title-number intec-grid-item-auto intec-cl-background">1</span>
                                        <span class="startshop-order-section-title-wrapper"><?= Loc::getMessage('SO_DEFAULT_SECTIONS_PROPERTIES') ?></span>
                                    </div>
                                    <div class="startshop-order-section-content">
                                        <?php foreach ($arResult['PROPERTIES'] as $arProperty) { ?>
                                            <?php $fPropertyDraw($arProperty, $arUser); ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (!empty($arResult['DELIVERIES'])) { ?>
                                <div class="startshop-order-section deliveries intec-grid intec-grid-o-vertical intec-grid-item-2 intec-grid-item-600-1">
                                    <div class="startshop-order-section-title intec-grid intec-grid-a-v-center">
                                        <span class="startshop-order-section-title-number intec-grid-item-auto intec-cl-background">2</span>
                                        <span class="startshop-order-section-title-wrapper"><?= Loc::getMessage('SO_DEFAULT_SECTIONS_DELIVERIES_DELIVERY') ?></span>
                                    </div>
                                    <div class="intec-form-field">
                                        <?php foreach ($arResult['DELIVERIES'] as $iDeliveryKey => $arDelivery) { ?>
                                            <label class="intec-form-label startshop-order-delivery intec-ui intec-ui-control-checkbox intec-ui-scheme-current intec-ui-size-3">
                                                <div class="startshop-order-delivery-wrapper intec-grid intec-grid-a-v-start">
                                                    <div class="startshop-order-delivery-input-wrap">
                                                        <input type="radio" name="DELIVERY" value="<?= $iDeliveryKey ?>" <?= $_REQUEST['DELIVERY'] == $iDeliveryKey ? 'checked="checked"' : ''?>>
                                                        <span class="startshop-order-delivery-input intec-ui-part-selector"></span>
                                                    </div>
                                                    <div class="startshop-order-delivery-title-wrap">
                                                        <div class="startshop-order-delivery-title"><?= htmlspecialcharsbx($arDelivery['LANG'][LANGUAGE_ID]['NAME']) ?></div>
                                                        <div class="startshop-order-delivery-price"><?= $arDelivery['PRICE']['VALUE'] > 0 ? $arDelivery['PRICE']['PRINT_VALUE'] : Loc::getMessage('SO_DEFAULT_SECTIONS_DELIVERIES_DELIVERY_FREE') ?></div>
                                                    </div>
                                                </div>
                                            </label>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (!empty($arResult['PAYMENTS'])) { ?>
                                <div class="startshop-order-section payments intec-grid intec-grid-o-vertical intec-grid-item-2 intec-grid-item-600-1">
                                    <div class="startshop-order-section-title intec-grid intec-grid-a-v-center">
                                        <span class="startshop-order-section-title-number intec-grid-item-auto intec-cl-background">3</span>
                                        <span class="startshop-order-section-title-wrapper"><?= Loc::getMessage('SO_DEFAULT_SECTIONS_PAYMENTS_PAYMENT') ?></span>
                                    </div>
                                    <div class="intec-form-field">
                                        <?php foreach ($arResult['PAYMENTS'] as $iPaymentKey => $arPayment) { ?>
                                            <label class="intec-form-label startshop-order-payment intec-ui intec-ui-control-checkbox intec-ui-scheme-current intec-ui-size-3">
                                                <div class="startshop-order-payment-wrapper intec-grid intec-grid-a-v-center">
                                                    <div class="startshop-order-payment-input-wrap">
                                                        <input type="radio" name="PAYMENT" value="<?= $iPaymentKey ?>" <?= $_REQUEST['DELIVERY'] == $iPaymentKey ? 'checked="checked"' : ''?>>
                                                        <span class="startshop-order-payment-input intec-ui-part-selector"></span>
                                                    </div>
                                                    <div class="startshop-order-payment-title">
                                                        <?= htmlspecialcharsbx($arPayment['LANG'][LANGUAGE_ID]['NAME']) ?>
                                                    </div>
                                                </div>
                                            </label>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (!empty($arResult['DELIVERIES'])) { ?>
                                <div class="startshop-order-section delivery-properties intec-grid-item-1">
                                    <div class="startshop-order-section-title intec-grid intec-grid-a-v-center">
                                        <span class="startshop-order-section-title-number intec-grid-item-auto intec-cl-background">4</span>
                                        <span class="startshop-order-section-title-wrapper"><?= Loc::getMessage('SO_DEFAULT_SECTIONS_DELIVERIES') ?></span>
                                    </div>
                                    <div class="startshop-order-section-content">
                                        <?php foreach ($arResult['DELIVERIES_PROPERTIES'] as $arDeliveryProperty) { ?>
                                            <?php $fPropertyDraw($arDeliveryProperty, $arUser) ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="startshop-order-section products intec-grid-item-1">
                                <div class="startshop-order-section-title intec-grid intec-grid-a-v-center">
                                    <span class="startshop-order-section-title-number intec-grid-item-auto intec-cl-background">5</span>
                                    <span class="startshop-order-section-title-wrapper"><?= Loc::getMessage('SO_DEFAULT_SECTIONS_ITEMS') ?></span>
                                    <div class="intec-grid-item"></div>
                                    <a class="startshop-order-section-basket-link" href="<?= $arParams['URL_BASKET'] ?>">
                                        <?= Loc::getMessage('SO_DEFAULT_BUTTON_BASKET') ?>
                                    </a>
                                </div>
                                <div class="startshop-order-section-content">
                                    <table class="startshop-order-table">
                                        <thead>
                                            <tr class="startshop-order-row">
                                                <td colspan="2" class="startshop-order-column header-name">
                                                    <div class="startshop-order-cell" style="white-space: nowrap;">
                                                        <?= Loc::getMessage('SO_DEFAULT_SECTIONS_ITEMS_COLUMN_NAME') ?>
                                                    </div>
                                                </td>
                                                <td class="startshop-order-column header-offer"></td>
                                                <td class="startshop-order-column header-price">
                                                    <div class="startshop-order-cell" style="white-space: nowrap;">
                                                        <?= Loc::getMessage('SO_DEFAULT_SECTIONS_ITEMS_COLUMN_PRICE') ?>
                                                    </div>
                                                </td>
                                                <td class="startshop-order-column header-quantity">
                                                    <div class="startshop-order-cell" style="white-space: nowrap;">
                                                        <?= Loc::getMessage('SO_DEFAULT_SECTIONS_ITEMS_COLUMN_QUANTITY') ?>
                                                    </div>
                                                </td>
                                                <td class="startshop-order-column header-sum">
                                                    <div class="startshop-order-cell" style="white-space: nowrap;">
                                                        <?= Loc::getMessage('SO_DEFAULT_SECTIONS_ITEMS_COLUMN_TOTAL') ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($arResult['ITEMS'] as $arItem) {

                                                $sPicture = $arItem['PREVIEW_PICTURE'];

                                                if (empty($sPicture))
                                                    $sPicture = $arItem['DETAIL_PICTURE'];

                                                if (!empty($sPicture)) {
                                                    $sPicture = CFile::ResizeImageGet($sPicture, [
                                                        'width' => 300,
                                                        'height' => 300
                                                    ], BX_RESIZE_IMAGE_PROPORTIONAL);

                                                    if (!empty($sPicture))
                                                        $sPicture = $sPicture['src'];
                                                }

                                                if (empty($sPicture))
                                                    $sPicture = SITE_TEMPLATE_PATH.'/images/picture.missing.png';

                                                $arSection = ArrayHelper::getValue($arItem, ['SECTION_INFO'], []);

                                                ?>
                                                <tr class="startshop-order-row">
                                                    <td class="startshop-order-column item-image">
                                                        <div class="startshop-order-cell">
                                                            <div class="item-image">
                                                                <a href="<?= $arItem['DETAIL_PAGE_URL'] ?>">
                                                                    <img loading="lazy" src="<?= $sPicture ?>" alt="<?= $arItem['NAME'] ?>">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="startshop-order-column item-name">
                                                        <div class="startshop-order-cell">
                                                            <?php if (!empty($arSection)) {?>
                                                                <div class="item-section">
                                                                    <a class="item-section-link" href="<?= $arSection['SECTION_PAGE_URL'] ?>">
                                                                        <?= $arSection['NAME'] ?>
                                                                    </a>
                                                                </div>
                                                            <?php }?>
                                                            <a class="item-name intec-cl-text-hover" href="<?= $arItem['DETAIL_PAGE_URL'] ?>">
                                                                <?= $arItem['NAME'] ?>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td class="startshop-order-column item-offer">
                                                        <?php if ($arItem['STARTSHOP']['OFFER']['OFFER']) { ?>
                                                            <div class="startshop-order-cell">
                                                                <?php foreach ($arItem['STARTSHOP']['OFFER']['PROPERTIES'] as $arProperty) { ?>
                                                                    <?php if ($arProperty['TYPE'] == 'TEXT') { ?>
                                                                        <div class="startshop-order-property text">
                                                                            <div class="property-name">
                                                                                <?= $arProperty['NAME'] ?>:
                                                                            </div>
                                                                            <div class="property-value">
                                                                                <?= $arProperty['VALUE']['TEXT'] ?>
                                                                            </div>
                                                                        </div>
                                                                    <?php } else { ?>
                                                                        <div class="startshop-order-property picture">
                                                                            <div class="property-name">
                                                                                <?= $arProperty['NAME'] ?>:
                                                                            </div>
                                                                            <div class="property-value">
                                                                                <div class="property-value-wrapper">
                                                                                    <img src="<?= $arProperty['VALUE']['PICTURE'] ?>"
                                                                                         alt="<?= $arProperty['VALUE']['TEXT'] ?>"
                                                                                         title="<?= $arProperty['VALUE']['TEXT'] ?>"
                                                                                         loading="lazy"/>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </div>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="startshop-order-column item-price">
                                                        <div class="startshop-order-cell" style="white-space: nowrap;">
                                                            <?= $arItem['STARTSHOP']['PRICES']['MINIMAL']['PRINT_VALUE'] ?>
                                                        </div>
                                                    </td>
                                                    <td class="startshop-order-column item-quantity">
                                                        <div class="startshop-order-cell" style="white-space: nowrap;">
                                                            <?= $arItem['STARTSHOP']['BASKET']['QUANTITY'] ?>
                                                        </div>
                                                    </td>
                                                    <td class="startshop-order-column item-sum">
                                                        <div class="startshop-order-cell" style="white-space: nowrap;">
                                                            <?= CStartShopCurrency::FormatAsString($arItem['STARTSHOP']['PRICES']['MINIMAL']['VALUE'] * $arItem['STARTSHOP']['BASKET']['QUANTITY'], $arParams['CURRENCY']) ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="startshop-order-summary intec-grid-item-4 intec-grid-item-1200-1">
                            <div class="startshop-order-summary-wrapper">
                                <div class="startshop-order-summary-wrapper-2">
                                    <div class="startshop-order-summary-title intec-grid intec-grid-a-v-center intec-grid-a-h-between">
                                        <div class="startshop-order-summary-title-wrapper">
                                            <?= Loc::getMessage('SO_DEFAULT_YOUR_ORDER') ?>
                                        </div>
                                        <a class="startshop-order-summary-basket-link" href="<?= $arParams['URL_BASKET'] ?>">
                                            <?= Loc::getMessage('SO_DEFAULT_BUTTON_BASKET_2') ?>
                                        </a>
                                    </div>
                                    <div class="startshop-order-summary-prices">
                                        <div class="startshop-order-summary-products intec-grid intec-grid-a-v-center intec-grid-a-h-between">
                                            <div class="startshop-order-summary-products-title">
                                                <span class="startshop-order-summary-products-title-wrapper">
                                                    <?= Loc::getMessage('SO_DEFAULT_TOTAL_ITEMS') ?>
                                                </span>
                                                <span class="startshop-order-summary-products-title-count">
                                                    <?= ' (' . count($arResult["ITEMS"]) . ')' ?>
                                                </span>
                                            </div>
                                            <div class="startshop-order-summary-products-value">
                                                <?= $arResult['SUM']['PRINT_VALUE'] ?>
                                            </div>
                                        </div>
                                        <?php if (!empty($arResult['DELIVERIES'])) { ?>
                                            <div class="startshop-order-delivery intec-grid intec-grid-a-v-center intec-grid-a-h-between">
                                                <div class="startshop-order-delivery-title">
                                                    <?= Loc::getMessage('SO_DEFAULT_TOTAL_DELIVERY') ?>
                                                </div>
                                                <div class="startshop-order-delivery-value">
                                                    <?= Loc::getMessage('SO_DEFAULT_TOTAL_DELIVERY_2') ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="startshop-order-summary-total intec-grid intec-grid-a-v-center intec-grid-a-h-between">
                                        <div class="startshop-order-summary-total-title">
                                            <?= Loc::getMessage('SO_DEFAULT_TOTAL') ?>
                                        </div>
                                        <div class="startshop-order-summary-total-value">
                                            <?= $arResult['SUM']['PRINT_VALUE'] ?>
                                        </div>
                                    </div>
                                </div>
                                <input type="submit" class="intec-button intec-button-cl-common intec-button-lg intec-button-block intec-button-fs-16"
                                   value="<?= Loc::getMessage('SO_DEFAULT_BUTTONS_ORDER') ?>" />
                                <div class="startshop-order-summary-consent">
                                    <?= Loc::getMessage('SO_DEFAULT_I_AGREED_TO') ?>
                                    <a href="<?= $arParams['URL_RULES_OF_PERSONAL_DATA_PROCESSING'] ?>" target="_blank"><?= Loc::getMessage('SO_DEFAULT_PROCESSING_PERSONAL_DATA') ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="startshop-order-checkout intec-grid">
                        <div class="startshop-order-checkout-button">
                            <input type="submit" class="intec-button intec-button-cl-common intec-button-lg"
                                   value="<?= Loc::getMessage('SO_DEFAULT_BUTTONS_ORDER') ?>" />
                        </div>
                        <div class="startshop-order-checkout-consent">
                            <?= Loc::getMessage('SO_DEFAULT_I_AGREED_TO') ?>
                            <a href="<?= $arParams['URL_RULES_OF_PERSONAL_DATA_PROCESSING'] ?>" target="_blank"><?= Loc::getMessage('SO_DEFAULT_PROCESSING_PERSONAL_DATA') ?></a>
                        </div>
                    </div>
                    <script type="text/javascript">
                        $(document).ready(function(){
                            var $oRoot = $('#<?= $sTemplateId ?>');
                            var $oDeliveries = <?= !empty($arResult['DELIVERIES']) ? JavaScript::toObject($arResult['DELIVERIES']) : '{}' ?>;
                            var $oCurrency = <?= !empty($arResult['CURRENCY']) ? JavaScript::toObject($arResult['CURRENCY']) : 'null' ?>;
                            var $sLanguageID = <?= JavaScript::toObject(LANGUAGE_ID) ?>;
                            var $oItemsSum = <?= JavaScript::toObject($arResult['SUM']) ?>;

                            function UpdateForm() {
                                var $iCurrentDelivery = $oRoot.find('[name=DELIVERY]:checked').val();
                                var $fDeliverySum = 0;
                                Startshop.Functions.forEach($oDeliveries, function($iKey, $oDelivery) {
                                    Startshop.Functions.forEach($oDelivery['PROPERTIES'], function ($iDeliveryPropertyKey, $oDeliveryProperty) {
                                        $oRoot.find('[name=PROPERTY_' + $iDeliveryPropertyKey + ']').parents('div.intec-form-field').hide();
                                    });
                                });

                                if ($iCurrentDelivery !== undefined) {
                                    Startshop.Functions.forEach($oDeliveries[$iCurrentDelivery]['PROPERTIES'], function($iDeliveryPropertyKey, $oDeliveryProperty) {
                                        $oRoot.find('[name=PROPERTY_' + $iDeliveryPropertyKey + ']').parents('div.intec-form-field').show();
                                    });

                                    $fDeliverySum = parseFloat($oDeliveries[$iCurrentDelivery]['PRICE']['VALUE']);
                                }

                                var $oDisplayedDeliveries = $oRoot.find(".startshop-order-section.delivery-properties div.intec-form-field[style*='display: block']");

                                if ($oDisplayedDeliveries.length === 0) {
                                    $oRoot.find('.startshop-order-section.delivery-properties').hide();
                                    $oRoot.find(".startshop-order-section.products .startshop-order-section-title-number").html('4');
                                } else {
                                    $oRoot.find('.startshop-order-section.delivery-properties').show();
                                    $oRoot.find(".startshop-order-section.products .startshop-order-section-title-number").html('5');
                                }

                                var $fTotalSum = parseFloat($oItemsSum['VALUE']) + parseFloat($fDeliverySum);
                                var $oFieldDelivery = $('.startshop-order-summary .startshop-order-delivery-value', $oRoot);
                                var $oFieldTotal = $('.startshop-order-summary .startshop-order-summary-total-value', $oRoot);

                                if ($oCurrency != null) {
                                    $fDeliverySum = Startshop.Functions.stringReplace(
                                        {
                                            '#': Startshop.Functions.numberFormat(
                                                $fDeliverySum,
                                                0,
                                                $oCurrency['FORMAT'][$sLanguageID]['DELIMITER_DECIMAL'],
                                                $oCurrency['FORMAT'][$sLanguageID]['DELIMITER_THOUSANDS']
                                            )
                                        },
                                        $oCurrency['FORMAT'][$sLanguageID]['FORMAT']
                                    );

                                    $fTotalSum = Startshop.Functions.stringReplace(
                                        {
                                            '#': Startshop.Functions.numberFormat(
                                                $fTotalSum,
                                                0,
                                                $oCurrency['FORMAT'][$sLanguageID]['DELIMITER_DECIMAL'],
                                                $oCurrency['FORMAT'][$sLanguageID]['DELIMITER_THOUSANDS']
                                            )
                                        },
                                        $oCurrency['FORMAT'][$sLanguageID]['FORMAT']
                                    );
                                }

                                $oFieldDelivery.html($fDeliverySum);
                                $oFieldTotal.html($fTotalSum);

                                var sections = $oRoot.find('.startshop-order-section:visible');
                                sections.each(function (index) {
                                    var section = $(this);
                                    var number = $('.startshop-order-section-title-number', section);

                                    number.html(index + 1);
                                });
                            }

                            $oRoot.find('[name=DELIVERY]').change(function () {
                                UpdateForm();
                            });

                            UpdateForm();
                        });
                    </script>
                </form>
            <?php } ?>
        </div>
    </div>
<?= Html::endTag('div') ?>
<?php unset($fPropertyDraw) ?>