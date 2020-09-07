<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use intec\core\bitrix\Component;
use intec\core\helpers\Html;
use intec\core\helpers\ArrayHelper;

/**
 * @global CMain $APPLICATION
 * @var array $arResult
 */


global $USER;

$arUser = $USER->GetID();
$arUser = CUser::GetByID($arUser);
$arUser = $arUser->Fetch();

$this->setFrameMode(false);
$sTemplateId = Html::getUniqueId(null, Component::getUniqueId($this));

$bInformationShow = false;

if (!empty($arUser))
    $bInformationShow = true;

foreach($arResult["PROPERTIES"] as $arProperty) {
    if (!empty($arProperty['VALUE']))
        $bInformationShow = true;
    
    if ($arProperty["USER_FIELD"] == "NAME")
        $sName = ArrayHelper::getValue($arProperty, 'VALUE');

    if ($arProperty["USER_FIELD"] == "LAST_NAME")
        $sLastName = ArrayHelper::getValue($arProperty, 'VALUE');
}

?>
<?= Html::beginTag('div', [
    'id' => $sTemplateId,
    'class' => [
        'ns-intec',
        'c-startshop-orders-detail',
        'c-startshop-orders-detail-default'
    ]
]) ?>
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <?if (!empty($arResult)){?>
                <div class="startshop-orders-detail-header">
                    <div class="startshop-orders-detail-header-wrapper">
                        <?= Loc::getMessage("SOD_DEFAULT_ORDER") ?><?= $arResult['ID'] ?> <?= Loc::getMessage("SOD_DEFAULT_FROM") ?> <?= $arResult["DATE_CREATE"] ?> <?= Loc::getMessage("SOD_DEFAULT_SUM") ?> <?= $arResult["AMOUNT"]["PRINT_VALUE"] ?>
                    </div>
                    <?php if (!empty($arUser)) { ?>
                        <a class="startshop-orders-detail-header-link intec-cl-text-hover intec-grid intec-grid-a-v-center" href="<?=$arParams["LIST_PAGE_URL"]?>">
                            <i class="intec-arrow-icon fal fa-angle-left"></i>
                            <?=Loc::getMessage("SOD_DEFAULT_RETURN")?>
                        </a>
                    <?php } ?>
                </div>
                <div class="startshop-orders-detail-content intec-grid intec-grid-wrap intec-grid-i-10">
                    <div class="startshop-orders-detail-section order intec-grid-item-2 intec-grid-item-1200-1 intec-grid intec-grid-o-vertical">
                        <div class="startshop-orders-detail-section-header">
                            <div class="startshop-orders-detail-section-header-wrapper">
                                <?=Loc::getMessage("SOD_DEFAULT_SECTION_ORDER_HEADER");?>
                            </div>
                        </div>
                        <div class="startshop-orders-detail-section-wrapper intec-grid-item">
                            <?php if (!empty($sLastName)) { ?>
                                <div class="startshop-orders-detail-section-field intec-grid intec-grid-400-wrap intec-grid-a-v-center intec-grid-a-h-between">
                                    <div class="startshop-orders-detail-section-field-title intec-grid-item-3 intec-grid-item-400-1">
                                        <?= Loc::getMessage("SOD_DEFAULT_SECTION_ORDER_LAST_NAME") ?>
                                    </div>
                                    <div class="startshop-orders-detail-section-field-value intec-grid-item intec-grid-item-400-1">
                                        <?= $sLastName ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (!empty($sName)) { ?>
                                <div class="startshop-orders-detail-section-field intec-grid intec-grid-400-wrap intec-grid-a-v-center intec-grid-a-h-between">
                                    <div class="startshop-orders-detail-section-field-title intec-grid-item-3 intec-grid-item-400-1">
                                        <?= Loc::getMessage("SOD_DEFAULT_SECTION_ORDER_NAME") ?>
                                    </div>
                                    <div class="startshop-orders-detail-section-field-value intec-grid-item intec-grid-item-400-1">
                                        <?= $sName ?>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="startshop-orders-detail-section-field intec-grid intec-grid-400-wrap intec-grid-a-v-center intec-grid-a-h-between">
                                <div class="startshop-orders-detail-section-field-title intec-grid-item-3 intec-grid-item-400-1">
                                    <?= Loc::getMessage("SOD_DEFAULT_SECTION_ORDER_SUM") ?>
                                </div>
                                <div class="startshop-orders-detail-section-field-value intec-grid-item intec-grid-item-400-1">
                                    <?= $arResult['AMOUNT']['PRINT_VALUE'] ?>
                                </div>
                            </div>
                            <div class="startshop-orders-detail-section-field intec-grid intec-grid-400-wrap intec-grid-a-v-center intec-grid-a-h-between">
                                <div class="startshop-orders-detail-section-field-title intec-grid-item-3 intec-grid-item-400-1">
                                    <?= Loc::getMessage("SOD_DEFAULT_SECTION_ORDER_STATUS") ?>
                                </div>
                                <div class="startshop-orders-detail-section-field-value status intec-grid-item intec-grid-item-400-1">
                                    <span class="startshop-orders-detail-section-field-value status-wrapper">
                                        <?= $arResult["STATUS"]["LANG"][LANGUAGE_ID]["NAME"] ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($bInformationShow) { ?>
                        <div class="startshop-orders-detail-section information intec-grid-item-2 intec-grid-item-1200-1 intec-grid intec-grid-o-vertical">
                            <div class="startshop-orders-detail-section-header">
                                <div class="startshop-orders-detail-section-header-wrapper">
                                    <?= Loc::getMessage("SOD_DEFAULT_SECTION_INFORMATION_HEADER") ?>
                                </div>
                            </div>
                            <div class="startshop-orders-detail-section-wrapper intec-grid-item">
                                <?php if (!empty($arUser)) { ?>
                                    <div class="startshop-orders-detail-section-field intec-grid intec-grid-400-wrap intec-grid-a-v-center intec-grid-a-h-between">
                                        <div class="startshop-orders-detail-section-field-title intec-grid-item-3 intec-grid-item-400-1">
                                            <?= Loc::getMessage("SOD_DEFAULT_SECTION_INFORMATION_LOGIN") ?>
                                        </div>
                                        <div class="startshop-orders-detail-section-field-value intec-grid-item intec-grid-item-400-1">
                                            <?= $arUser["LOGIN"] ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <?php foreach($arResult["PROPERTIES"] as $arProperty) { ?>
                                <?php if (empty($arProperty['VALUE'])) continue ?>
                                    <div class="startshop-orders-detail-section-field intec-grid intec-grid-400-wrap intec-grid-a-v-center intec-grid-a-h-between">
                                        <div class="startshop-orders-detail-section-field-title intec-grid-item-3 intec-grid-item-400-1">
                                            <?= $arProperty["LANG"][LANGUAGE_ID]["NAME"] ?>
                                        </div>
                                        <div class="startshop-orders-detail-section-field-value intec-grid-item intec-grid-item-400-1">
                                            <?= $arProperty["VALUE"] ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (!empty($arResult['PAYMENT'])) { ?>
                        <div class="startshop-orders-detail-section payment intec-grid-item-2 intec-grid-item-1200-1">
                            <div class="startshop-orders-detail-section-wrapper intec-grid intec-grid-wrap intec-grid-a-v-center intec-grid-a-h-between">
                                <div class="startshop-orders-detail-section-title intec-grid-item-2 intec-grid-item-400-1">
                                    <?=Loc::getMessage("SOD_DEFAULT_SECTION_PAYMENT_HEADER");?>
                                </div>
                                <div class="startshop-orders-detail-section-value intec-grid intec-grid-a-v-center intec-grid-item-auto intec-grid-item-400-1">
                                    <i class="startshop-orders-detail-section-value-icon fa fa-check intec-cl-background"></i>
                                    <div class="startshop-orders-detail-section-value-wrapper">
                                        <?= $arResult['PAYMENT']['LANG'][LANGUAGE_ID]['NAME'] ?>
                                    </div>
                                </div>
                                <?php if (!empty($arResult['PAYMENT']['HANDLER']) && $arResult['PAYED'] != 'Y' && $arResult['STATUS']['CAN_PAY'] == 'Y' && $arResult['PAYMENT']['ACTIVE'] == 'Y') { ?>
                                <div class="startshop-orders-detail-section-button intec-grid-item-1">
                                    <? CStartShopPayment::ShowPayForm($arResult['PAYMENT']['ID'], [
                                        "BUTTON_NAME" => Loc::getMessage('SOD_DEFAULT_SECTION_PROPERTY_PAYMENT_BUTTON'),
                                        "BUTTON_CLASS" => "startshop-orders-detail-section-button-wrapper intec-button intec-button-cl-common intec-button-md",
                                        "ORDER_ID" => $arResult['ID'],
                                        "ORDER_SUM" => CStartShopCurrency::Convert($arResult['~AMOUNT'], $arResult['~CURRENCY'], $arResult['PAYMENT']['CURRENCY']),
                                        "ORDER_ITEMS" => array_keys($arResult["ITEMS"]),
                                        "CULTURE" => LANGUAGE_ID
                                    ])?>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (!empty($arResult['DELIVERY'])) { ?>
                        <div class="startshop-orders-detail-section delivery intec-grid-item-2 intec-grid-item-1200-1">
                            <div class="startshop-orders-detail-section-wrapper intec-grid intec-grid-wrap intec-grid-a-v-center intec-grid-a-h-between">
                                <div class="startshop-orders-detail-section-title intec-grid-item-2 intec-grid-item-400-1">
                                    <?=Loc::getMessage("SOD_DEFAULT_SECTION_DELIVERY_HEADER");?>
                                </div>
                                <div class="startshop-orders-detail-section-value intec-grid intec-grid-a-v-center intec-grid-item-auto intec-grid-item-400-1">
                                    <i class="startshop-orders-detail-section-value-icon fa fa-check intec-cl-background"></i>
                                    <div class="startshop-orders-detail-section-value-wrapper">
                                        <?= $arResult['DELIVERY']['LANG'][LANGUAGE_ID]['NAME'] ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if (!empty($arResult['ITEMS'])) { ?>
                    <div class="startshop-orders-detail-section products intec-grid-item-1">
                        <div class="startshop-orders-detail-section-header">
                            <div class="startshop-orders-detail-section-header-wrapper">
                                <?= Loc::getMessage("SOD_DEFAULT_SECTION_PRODUCTS_HEADER") ?>
                            </div>
                        </div>
                        <div class="startshop-orders-detail-section-wrapper">
                            <table class="startshop-orders-detail-products">
                                <?php foreach ($arResult['ITEMS'] as $iKey => $arItem) {

                                    $sPicture = $arItem['ELEMENT']['PREVIEW_PICTURE'];

                                    if (empty($sPicture))
                                        $sPicture = $arItem['ELEMENT']['DETAIL_PICTURE'];

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

                                ?>
                                <tr class="startshop-orders-detail-product">
                                    <td class="startshop-orders-detail-product-image">
                                        <a class="startshop-orders-detail-product-image-wrapper" style="background-image:url(<?= $sPicture ?>)"></a>
                                    </td>
                                    <td class="startshop-orders-detail-product-title">
                                        <div class="startshop-orders-detail-product-cell-name">
                                            <?= $arItem['SECTION']['NAME'] ?>
                                        </div>
                                        <a class="startshop-orders-detail-product-cell-value intec-cl-text-hover" href="<?= $arItem['ELEMENT']['DETAIL_PAGE_URL'] ?>">
                                            <?= $arItem['NAME'] ?>
                                        </a>
                                    </td>
                                    <td class="startshop-orders-detail-product-offers">
                                        <?php if (!empty($arItem['ELEMENT']['STARTSHOP']['OFFER']['PROPERTIES'])) { ?>
                                            <div class="startshop-orders-detail-product-cell-value">
                                                <?php foreach ($arItem['ELEMENT']['STARTSHOP']['OFFER']['PROPERTIES'] as $arProperty) { ?>
                                                    <?php if ($arProperty['TYPE'] == 'TEXT') { ?>
                                                        <div class="startshop-orders-detail-property text">
                                                            <div class="property-name">
                                                                <?= $arProperty['NAME'] ?>:
                                                            </div>
                                                            <div class="property-value">
                                                                <?= $arProperty['VALUE']['TEXT'] ?>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="startshop-orders-detail-property picture">
                                                            <div class="property-name">
                                                                <?= $arProperty['NAME'] ?>:
                                                            </div>
                                                            <div class="property-value">
                                                                <div class="property-value-wrapper">
                                                                    <img src="<?= $arProperty['VALUE']['PICTURE'] ?>"
                                                                         alt="<?= $arProperty['VALUE']['TEXT'] ?>"
                                                                         title="<?= $arProperty['VALUE']['TEXT'] ?>"
                                                                         loading="lazy">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } ?>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    </td>
                                    <td class="startshop-orders-detail-product-price">
                                        <div class="startshop-orders-detail-product-cell-name">
                                            <?= Loc::getMessage('SOD_DEFAULT_SECTION_PRODUCTS_PRICE') ?>
                                        </div>
                                        <div class="startshop-orders-detail-product-cell-value">
                                            <?= $arItem["PRICE"]["PRINT_VALUE"] ?>
                                        </div>
                                    </td>
                                    <td class="startshop-orders-detail-product-quantity">
                                        <div class="startshop-orders-detail-product-cell-name">
                                            <?= Loc::getMessage('SOD_DEFAULT_SECTION_PRODUCTS_QUANTITY') ?>
                                        </div>
                                        <div class="startshop-orders-detail-product-cell-value">
                                            <?= $arItem["QUANTITY"] ?>
                                        </div>
                                    </td>
                                    <td class="startshop-orders-detail-product-sum">
                                        <div class="startshop-orders-detail-product-cell-name">
                                            <?= Loc::getMessage('SOD_DEFAULT_SECTION_PRODUCTS_SUM') ?>
                                        </div>
                                        <div class="startshop-orders-detail-product-cell-value">
                                            <?= $arItem["AMOUNT"]["PRINT_VALUE"] ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </table>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
<?= Html::endTag('div') ?>
