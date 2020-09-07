<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use intec\core\helpers\ArrayHelper;
use \Bitrix\Main\Localization\Loc;

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$this->addExternalCss("/bitrix/css/main/bootstrap.css");

$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
	'TEMPLATE_CLASS' => 'bx-'.$arParams['TEMPLATE_THEME'],
	'CURRENCIES' => CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true)
);
$arMainProduct = ArrayHelper::getValue($arResult, 'ELEMENT');
$sNoPhotoSrc = $this->GetFolder().'/images/no_foto.png';
$curJsId = $this->randString().$arMainProduct['ID'];
if ($arMainProduct['DETAIL_PICTURE']['src']){
    $sMainPicture = $arMainProduct['DETAIL_PICTURE']['src'];
} else {
    $sMainPicture = $sNoPhotoSrc;
}
?>
<div id="bx-set-const-<?=$curJsId?>" class="ns-bitrix c-catalog-set-constructor c-catalog-set-constructor-default">
    <div class="intec-content">
        <div class="intec-content-wrapper">
            <? if ($arParams['SHOW_TITLE'] == 'Y') {?>
                <div class="item-sub-title"><?= Loc::GetMessage('CATALOG_SET_CONSTRUCTOR_TITLE') ?></div>
            <? } ?>
            <div class="catalog-set-constructor-block-wrapper clearfix">
                <div class="catalog-set-constructor-block-items">
                    <div class="catalog-set-constructor-main-element catalog-set-constructor-table">
                        <div class="catalog-set-constructor-table-row">
                            <div class="catalog-set-constructor-table-cell catalog-set-constructor-switch">
                                <div class="universe-settings-item-wrapper intec-no-select">
                                    <input type="checkbox" id="" name="show_bg" value="0" data-ui-switch="{}" checked="checked" disabled>
                                </div>
                            </div>
                            <div class="catalog-set-constructor-table-cell catalog-set-constructor-main-element-image-wrap">
                                <div class="catalog-set-constructor-main-element-image" style="background-image: url('<?= $sMainPicture; ?>')"></div>
                            </div>
                            <div class="catalog-set-constructor-main-element-name-wrapper catalog-set-constructor-table-cell">
                                <div class="catalog-set-constructor-main-element-name"><?= $arMainProduct["NAME"]; ?></div>
                                <div class="catalog-set-constructor-main-element-quantity">
                                    <?if ($arMainProduct["CATALOG_QUANTITY"] > 0){?>
                                        <?=Loc::GetMessage("CATALOG_SET_AVAILABLE")?> <?= $arMainProduct["CATALOG_QUANTITY"]; ?> <?=$arMainProduct["MEASURE"]["SYMBOL_RUS"];?>
                                    <?}else{?>
                                        <?=Loc::GetMessage("CATALOG_SET_NOT_AVAILABLE")?>
                                    <?}?>
                                </div>
                            </div>
                            <div class="catalog-set-constructor-main-element-added-count catalog-set-constructor-table-cell">
                                <?=$arMainProduct["BASKET_QUANTITY"];?>
                                <?=$arMainProduct["MEASURE"]["SYMBOL_RUS"];?>
                            </div>
                            <div class="catalog-set-constructor-main-element-price catalog-set-constructor-table-cell">
                                <?if (!($arMainProduct["PRICE_VALUE"] == $arMainProduct["PRICE_DISCOUNT_VALUE"])):?>
                                    <div class="catalog-set-constructor-main-element-price-old">
                                        <?=$arMainProduct["PRICE_PRINT_VALUE"]?>
                                    </div>
                                <?endif?>
                                <div class="catalog-set-constructor-main-element-price-current">
                                    <?=$arMainProduct["PRICE_PRINT_DISCOUNT_VALUE"]?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="catalog-set-constructor-set-title"><?= Loc::getMessage('CATALOG_SET_OTHER_TITLE'); ?></div>

                    <div class="catalog-set-constructor-main-set">
                        <div class="catalog-set-constructor-main-set-items catalog-set-constructor-table" data-role="set-items">
                            <?foreach($arResult["SET_ITEMS"]["DEFAULT"] as $key => $arItem):

                                if ($arItem['DETAIL_PICTURE']['src']){
                                    $sSetPicture = $arItem['DETAIL_PICTURE']['src'];
                                } else {
                                    $sSetPicture = $sNoPhotoSrc;
                                }
                                ?>
                                <div class="catalog-set-constructor-main-set-item catalog-set-constructor-table-row append"
                                        data-id="<?=$arItem["ID"]?>"
                                        data-img="<?=$arItem["DETAIL_PICTURE"]["src"]?>"
                                        data-url="<?=$arItem["DETAIL_PAGE_URL"]?>"
                                        data-name="<?=$arItem["NAME"]?>"
                                        data-price="<?=$arItem["PRICE_DISCOUNT_VALUE"]?>"
                                        data-print-price="<?=$arItem["PRICE_PRINT_DISCOUNT_VALUE"]?>"
                                        data-old-price="<?=$arItem["PRICE_VALUE"]?>"
                                        data-print-old-price="<?=$arItem["PRICE_PRINT_VALUE"]?>"
                                        data-diff-price="<?=$arItem["PRICE_DISCOUNT_DIFFERENCE_VALUE"]?>"
                                        data-measure="<?=$arItem["MEASURE"]["SYMBOL_RUS"];?>"
                                        data-quantity="<?=$arItem["BASKET_QUANTITY"];?>"
                                >
                                    <div class=" catalog-set-constructor-table-cell catalog-set-constructor-switch">
                                        <div class="universe-settings-item-wrapper intec-no-select">
                                            <input type="checkbox" id="" name="show_bg" value="0" data-ui-switch="{}" checked="checked" data-role="change-item">
                                        </div>
                                    </div>
                                    <div class="catalog-set-constructor-table-cell catalog-set-constructor-main-element-image-wrap">
                                        <div class="catalog-set-constructor-main-element-image" style="background-image: url('<?= $sSetPicture; ?>')"></div>
                                    </div>
                                    <div class="catalog-set-constructor-main-element-name-wrapper catalog-set-constructor-table-cell">
                                        <div class="catalog-set-constructor-main-element-name"><?= $arItem["NAME"]; ?></div>
                                        <div class="catalog-set-constructor-main-element-quantity">
                                            <?if ($arItem["CATALOG_QUANTITY"] > 0){?>
                                                <?=Loc::GetMessage("CATALOG_SET_AVAILABLE")?> <?= $arItem["CATALOG_QUANTITY"]; ?> <?=$arItem["MEASURE"]["SYMBOL_RUS"];?>
                                            <?}else{?>
                                                <?=Loc::GetMessage("CATALOG_SET_NOT_AVAILABLE")?>
                                            <?}?>
                                        </div>
                                    </div>
                                    <div class="catalog-set-constructor-main-element-added-count catalog-set-constructor-table-cell">
                                        <?=$arItem["BASKET_QUANTITY"];?>
                                        <?=$arItem["MEASURE"]["SYMBOL_RUS"];?>
                                    </div>
                                    <div class="catalog-set-constructor-main-element-price catalog-set-constructor-table-cell">
                                        <?if (!($arItem["PRICE_VALUE"] == $arItem["PRICE_DISCOUNT_VALUE"])):?>
                                            <div class="catalog-set-constructor-main-element-price-old">
                                                <?=$arItem["PRICE_PRINT_VALUE"]?>
                                            </div>
                                        <?endif?>
                                        <div class="catalog-set-constructor-main-element-price-current">
                                            <?=$arItem["PRICE_PRINT_DISCOUNT_VALUE"]?>
                                        </div>
                                    </div>
                                </div>
                            <?endforeach?>
                            <?
                            foreach($arResult["SET_ITEMS"]["OTHER"] as $key => $arItem):
                                if ($arItem['DETAIL_PICTURE']['src']){
                                    $sSetPicture = $arItem['DETAIL_PICTURE']['src'];
                                } else {
                                    $sSetPicture = $sNoPhotoSrc;
                                }
                                ?>
                                <div class="catalog-set-constructor-main-set-item catalog-set-constructor-table-row"
                                     data-id="<?=$arItem["ID"]?>"
                                     data-img="<?=$arItem["DETAIL_PICTURE"]["src"]?>"
                                     data-url="<?=$arItem["DETAIL_PAGE_URL"]?>"
                                     data-name="<?=$arItem["NAME"]?>"
                                     data-price="<?=$arItem["PRICE_DISCOUNT_VALUE"]?>"
                                     data-print-price="<?=$arItem["PRICE_PRINT_DISCOUNT_VALUE"]?>"
                                     data-old-price="<?=$arItem["PRICE_VALUE"]?>"
                                     data-print-old-price="<?=$arItem["PRICE_PRINT_VALUE"]?>"
                                     data-diff-price="<?=$arItem["PRICE_DISCOUNT_DIFFERENCE_VALUE"]?>"
                                     data-measure="<?=$arItem["MEASURE"]["SYMBOL_RUS"];?>"
                                     data-quantity="<?=$arItem["BASKET_QUANTITY"];?>"
                                >
                                    <div class=" catalog-set-constructor-table-cell catalog-set-constructor-switch">
                                        <div class="universe-settings-item-wrapper intec-no-select">
                                            <input type="checkbox" id="" name="show_bg" value="0" data-ui-switch="{}" <?=($arItem['CAN_BUY'])?"data-role=\"change-item\"":"disabled"?>>
                                        </div>
                                    </div>
                                    <div class="catalog-set-constructor-table-cell catalog-set-constructor-main-element-image-wrap">
                                        <div class="catalog-set-constructor-main-element-image" style="background-image: url('<?= $sSetPicture; ?>')"></div>
                                    </div>
                                    <div class="catalog-set-constructor-main-element-name-wrapper catalog-set-constructor-table-cell">
                                        <div class="catalog-set-constructor-main-element-name"><?= $arItem["NAME"]; ?></div>
                                        <div class="catalog-set-constructor-main-element-quantity">
                                            <?if ($arItem["CATALOG_QUANTITY"] > 0){?>
                                                <?=Loc::GetMessage("CATALOG_SET_AVAILABLE")?> <?= $arItem["CATALOG_QUANTITY"]; ?> <?=$arItem["MEASURE"]["SYMBOL_RUS"];?>
                                            <?}else{?>
                                                <?=Loc::GetMessage("CATALOG_SET_NOT_AVAILABLE")?>
                                            <?}?>
                                        </div>
                                    </div>
                                    <div class="catalog-set-constructor-main-element-added-count catalog-set-constructor-table-cell">
                                        <?=$arItem["BASKET_QUANTITY"];?>
                                        <?=$arItem["MEASURE"]["SYMBOL_RUS"];?>
                                    </div>
                                    <div class="catalog-set-constructor-main-element-price catalog-set-constructor-table-cell">
                                        <?if (!($arItem["PRICE_VALUE"] == $arItem["PRICE_DISCOUNT_VALUE"])):?>
                                            <div class="catalog-set-constructor-main-element-price-old">
                                                <?=$arItem["PRICE_PRINT_VALUE"]?>
                                            </div>
                                        <?endif?>
                                        <div class="catalog-set-constructor-main-element-price-current">
                                            <?=$arItem["PRICE_PRINT_DISCOUNT_VALUE"]?>
                                        </div>
                                    </div>
                                </div>
                            <?endforeach?>
                        </div>
                        <div style="display: none;margin:20px;" data-set-message="empty-set"></div>
                    </div>
                </div>
                <div class="catalog-set-constructor-block-result-wrap">
                    <div class="catalog-set-constructor-block-result-wrapper">
                        <div class="catalog-set-constructor-block-result" <?/*style="display: <?=($arResult['SHOW_DEFAULT_SET_DISCOUNT'] ? 'block' : 'none'); ?>;"*/?>>
                            <div class="catalog-set-constructor-block-result-title" ><?=Loc::GetMessage("CATALOG_SET_SET_PRICE")?></div>
                            <div class="catalog-set-constructor-block-result-value" data-role="set-old-price">
                                <?if ($arResult["SET_ITEMS"]["OLD_PRICE"] > 0){
                                    echo $arResult["SET_ITEMS"]["OLD_PRICE"];
                                }else{
                                    echo $arResult["SET_ITEMS"]["PRICE"];
                                }?>
                            </div>
                        </div>
                         <div class="catalog-set-constructor-block-result" <?/*style="display: <?=($arResult['SHOW_DEFAULT_SET_DISCOUNT'] ? 'block' : 'none'); ?>;"*/?>>
                            <div class="catalog-set-constructor-block-result-title" ><?=Loc::GetMessage("CATALOG_SET_ECONOMY_PRICE")?></div>
                            <div class="catalog-set-constructor-block-result-value" data-role="set-diff-price">
                                <? if ( $arResult["SET_ITEMS"]["PRICE_DISCOUNT_DIFFERENCE"] == 0 ){
                                    echo CurrencyFormat($arResult["SET_ITEMS"]["PRICE_DISCOUNT_DIFFERENCE"], $arMainProduct["PRICE_CURRENCY"]);
                                } else {
                                    echo $arResult["SET_ITEMS"]["PRICE_DISCOUNT_DIFFERENCE"];
                                }?>
                            </div>
                        </div>

                        <div class="catalog-set-constructor-block-result">
                            <div class="catalog-set-constructor-block-result-title" ><?=Loc::GetMessage("CATALOG_SET_SUM")?></div>
                            <div class="catalog-set-constructor-block-result-value" data-role="set-price"><?=$arResult["SET_ITEMS"]["PRICE"]?></div>
                        </div>

                        <div class="">
                            <div class="catalog-set-constructor-result-btn">
                                <a href="javascript:void(0)" data-role="set-buy-btn" class="intec-button intec-button-cl-common intec-button-md intec-button-s-7 intec-button-fs-16 intec-button-block intec-basket-button"
                                    <?=($arMainProduct["CAN_BUY"] ? '' : 'style="display: none;"')?>>
                                    <span class="intec-button-w-icon intec-basket glyph-icon-cart"></span>
                                    <span class="intec-basket-text"><?= $buyBtnMessage ?></span>
                                    <?=Loc::GetMessage("CATALOG_SET_BUY")?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?
$arJsParams = array(
    "numSetItems" => count($arResult["SET_ITEMS"]["DEFAULT"]),
    "jsId" => $curJsId,
    "parentContId" => "bx-set-const-".$curJsId,
    "ajaxPath" => $this->GetFolder().'/ajax.php',
    "canBuy" => $arResult["ELEMENT"]["CAN_BUY"],
    "currency" => $arResult["ELEMENT"]["PRICE_CURRENCY"],
    "mainElementPrice" => $arResult["ELEMENT"]["PRICE_DISCOUNT_VALUE"],
    "mainElementOldPrice" => $arResult["ELEMENT"]["PRICE_VALUE"],
    "mainElementDiffPrice" => $arResult["ELEMENT"]["PRICE_DISCOUNT_DIFFERENCE_VALUE"],
    "mainElementBasketQuantity" => $arResult["ELEMENT"]["BASKET_QUANTITY"],
    "lid" => SITE_ID,
    "iblockId" => $arParams["IBLOCK_ID"],
    "basketUrl" => $arParams["BASKET_URL"],
    "setIds" => $arResult["DEFAULT_SET_IDS"],
    "offersCartProps" => $arParams["OFFERS_CART_PROPERTIES"],
    "itemsRatio" => $arResult["BASKET_QUANTITY"],
    "messages" => array(
        "EMPTY_SET" => Loc::GetMessage('CT_BCE_CATALOG_MESS_EMPTY_SET'),
        "ADD_BUTTON" => Loc::GetMessage("CATALOG_SET_BUTTON_ADD")
    )
);
?>

<script type="text/javascript">
    intec.ui.update();
    BX.ready(function(){
       $obj = new BX.Catalog.SetConstructor(<?=CUtil::PhpToJSObject($arJsParams, false, true, true)?>);
    });
</script>