<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$curJsId = $this->randString();
?>
<div class="kit_set">

<div class="bx-modal-small-title puzzle_block__title fonts__middle_title">
    <?=GetMessage("CATALOG_SET_BUY_SET")?>
</div>

<div id="bx-set-const-<?=$curJsId?>" class="bx-set-constructor <?=$templateData['TEMPLATE_CLASS'];?>">
    <div class="row set__wrapper">
        <div class="col-sm-3 set-border-right set__product">
            <div class="bx-original-item-container">
                <?if ($arResult["ELEMENT"]["DETAIL_PICTURE"]["src"]):?>
                    <img src="<?=$arResult["ELEMENT"]["DETAIL_PICTURE"]["src"]?>"
                         class="bx-original-item-image"
                         alt="<?=($arResult['ELEMENT']['SEO']['ELEMENT_DETAIL_PICTURE_FILE_ALT'])?$arResult['ELEMENT']['SEO']['ELEMENT_DETAIL_PICTURE_FILE_ALT']:$arResult['ELEMENT']['NAME']?>"
                         title="<?=($arResult['ELEMENT']['SEO']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'])?$arResult['ELEMENT']['SEO']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']:$arResult['ELEMENT']['NAME']?>"
                    >
                <?else:?>
                    <img src="<?=$this->GetFolder().'/images/no_foto.png'?>"
                         class="bx-original-item-image"
                         alt="<?=($arResult['ELEMENT']['SEO']['ELEMENT_DETAIL_PICTURE_FILE_ALT'])?$arResult['ELEMENT']['SEO']['ELEMENT_DETAIL_PICTURE_FILE_ALT']:$arResult['ELEMENT']['NAME']?>"
                         title="<?=($arResult['ELEMENT']['SEO']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'])?$arResult['ELEMENT']['SEO']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']:$arResult['ELEMENT']['NAME']?>"
                    >
                <?endif?>

                <span class="bx-added-item-name">
                    <?=$arResult["ELEMENT"]["NAME"]?>
                </span>
                <span class="bx-added-item-new-price">
                    <strong><?=$arResult["ELEMENT"]["PRICE_PRINT_DISCOUNT_VALUE"]?></strong> * <?=$arResult["ELEMENT"]["BASKET_QUANTITY"];?> <?=$arResult["ELEMENT"]["MEASURE"]["SYMBOL_RUS"];?>
                </span>
                <?if (!($arResult["ELEMENT"]["PRICE_VALUE"] == $arResult["ELEMENT"]["PRICE_DISCOUNT_VALUE"])):?>
                    <span class="bx-catalog-set-item-price-old"><strong><?=$arResult["ELEMENT"]["PRICE_PRINT_VALUE"]?></strong></span>
                <?endif?>
            </div>
        </div>
        <div class="col-sm-9 set__list-product">
            <div class="row">
                <div class="col-xs-12">
                    <div class="bx-added-item-table-container">
                        <table class="bx-added-item-table">
                            <tbody data-role="set-items">
                            <?foreach($arResult["SET_ITEMS"]["DEFAULT"] as $key => $arItem):
	                            ?>
                                <tr
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
                                    <td class="bx-added-item-table-cell-img">
	                                    <a title="<?=$arItem["NAME"]?>" href="<?=$arItem["DETAIL_PAGE_URL"]?>">
	                                        <?if ($arItem["DETAIL_PICTURE"]["src"]):
		                                        ?>
	                                            <img
			                                         src="<?=$arItem["DETAIL_PICTURE"]["src"]?>"
	                                                 class="img-responsive"
			                                         alt="<?=($arItem['SEO']['ELEMENT_PREVIEW_PICTURE_FILE_ALT'])?$arItem['SEO']['ELEMENT_PREVIEW_PICTURE_FILE_ALT']:$arItem['NAME']?>"
		                                        title="<?=($arItem['SEO']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'])?$arItem['SEO']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']:$arItem['NAME']?>"
	                                            >
	                                        <?else:?>
	                                            <img src="<?=$this->GetFolder().'/images/no_foto.png'?>" class="img-responsive"
	                                                 alt="<?=($arItem['SEO']['ELEMENT_PREVIEW_PICTURE_FILE_ALT'])?$arItem['SEO']['ELEMENT_PREVIEW_PICTURE_FILE_ALT']:$arItem['NAME']?>"
		                                        title="<?=($arItem['SEO']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'])?$arItem['SEO']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']:$arItem['NAME']?>"
	                                            >
	                                        <?endif?>
	                                    </a>
                                    </td>
                                    <td class="bx-added-item-table-cell-itemname">
                                        <a class="tdn" title="<?=$arItem["NAME"]?>" href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a>
                                    </td>
                                    <td class="bx-added-item-table-cell-price">
                                        <span class="bx-added-item-new-price">
                                            <?/*=$arItem["PRICE_PRINT_DISCOUNT_VALUE"]?> * <?=$arItem["BASKET_QUANTITY"];?> <?=$arItem["MEASURE"]["SYMBOL_RUS"];*/?>
                                            <span class="bx-added-item-new-quantity"><?=GetMessage("CATALOG_SET_TOTAL_QUANTITY")?>: </span><?=$arItem["BASKET_QUANTITY"];?> <?=$arItem["MEASURE"]["SYMBOL_RUS"];?> <span class="bx-added-item-new-price-value"><?=$arItem["PRICE_PRINT_DISCOUNT_VALUE"]?></span>
                                        </span>
                                        <?if ($arItem["PRICE_VALUE"] != $arItem["PRICE_DISCOUNT_VALUE"]):?>
                                            <span class="bx-added-item-old-price"><?=$arItem["PRICE_PRINT_VALUE"]?></span>
                                        <?endif?>
                                    </td>
                                    <td class="bx-added-item-table-cell-del">
                                        
                                    <svg class="bx-added-item-delete" data-role="set-delete-btn" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                        width="13px" height="13px" viewBox="0 0 357 357" style="enable-background:new 0 0 357 357;" xml:space="preserve">
                                    <g>
                                        <g id="close">
                                            <polygon points="357,35.7 321.3,0 178.5,142.8 35.7,0 0,35.7 142.8,178.5 0,321.3 35.7,357 178.5,214.2 321.3,357 357,321.3 
                                                214.2,178.5 		"/>
                                        </g>
                                    </g>
                                    </svg>
                                        </td>
                                    <!-- <div class="bx-added-item-delete" data-role="set-delete-btn"></div></td> -->
                                </tr>
                            <?endforeach?>
                            </tbody>
                        </table><div style="display: none;margin:20px;" data-set-message="empty-set"></div>
                    </div>
                </div>
            </div>
            <div class="row" data-role="slider-parent-container"<?=(empty($arResult["SET_ITEMS"]["OTHER"]) ? 'style="display:none;"' : '')?>>
                <div class="col-xs-12">
                    <div class="bx-catalog-set-topsale-slider">
                        <div class="bx-catalog-set-topsale-slider-box">
                            <div class="bx-catalog-set-topsale-slider-container">
                                <div class="bx-catalog-set-topsale-slids bx-catalog-set-topsale-slids-<?=$curJsId?>" data-role="set-other-items">
                                    <?
                                    $first = true;
                                    foreach($arResult["SET_ITEMS"]["OTHER"] as $key => $arItem):?>
                                        <div class="bx-catalog-set-item-container bx-catalog-set-item-container-<?=$curJsId?>"
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
                                             data-quantity="<?=$arItem["BASKET_QUANTITY"];?>"<?
                                        if (!$arItem['CAN_BUY'] && $first)
                                        {
                                            echo 'data-not-avail="yes"';
                                            $first = false;
                                        }
                                        ?>
                                        >
                                            <div class="bx-catalog-set-item">
                                                <div class="bx-catalog-set-item-img">
                                                        <div class="bx-catalog-set-item-img-container">
                                                            <?if ($arItem["DETAIL_PICTURE"]["src"]):?>
                                                                <img
	                                                                src="<?=$arItem["DETAIL_PICTURE"]["src"]?>" class="img-responsive"
	                                                                alt="<?=($arItem['SEO']['ELEMENT_PREVIEW_PICTURE_FILE_ALT'])?$arItem['SEO']['ELEMENT_PREVIEW_PICTURE_FILE_ALT']:''?>"
	                                                                title="<?=($arItem['SEO']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'])?$arItem['SEO']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']:''?>"
	                                                            />
                                                            <?else:?>
                                                                <img
		                                                            src="<?=$this->GetFolder().'/images/no_foto.png'?>"
		                                                            class="img-responsive"
		                                                            alt="<?=($arItem['SEO']['ELEMENT_PREVIEW_PICTURE_FILE_ALT'])?$arItem['SEO']['ELEMENT_PREVIEW_PICTURE_FILE_ALT']:''?>
	                                                                title="<?=($arItem['SEO']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'])?$arItem['SEO']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']:''?>"

	                                                            />
                                                            <?endif?>
                                                        </div>
                                                    </div>
                                                <div class="bx-catalog-set-item-title">
                                                        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" title="<?=$arItem['NAME']?>"><?=$arItem["NAME"]?></a>
                                                    </div>
                                                <div class="bx-catalog-set-item-price">
                                                        <div class="bx-catalog-set-item-price-new">
                                                            <?=$arItem["BASKET_QUANTITY"];?> <?=$arItem["MEASURE"]["SYMBOL_RUS"];?> <span><?=$arItem["PRICE_PRINT_DISCOUNT_VALUE"]?></span>
                                                        </div>
                                                        <?if ($arItem["PRICE_VALUE"] != $arItem["PRICE_DISCOUNT_VALUE"]):?>
                                                            <div class="bx-catalog-set-item-price-old"><?=$arItem["PRICE_PRINT_VALUE"]?></div>
                                                        <?endif?>
                                                    </div>
                                                <div class="bx-catalog-set-item-add-btn">
                                                        <?
                                                        if ($arItem['CAN_BUY'])
                                                        {
                                                            ?><a href="javascript:void(0)" data-role="set-add-btn" class="set_add_btn"><?=GetMessage("CATALOG_SET_BUTTON_ADD")?></a><?
                                                        }
                                                        else
                                                        {
                                                            ?><span class="bx-catalog-set-item-notavailable"><?=GetMessage('CATALOG_SET_MESS_NOT_AVAILABLE');?></span><?
                                                        }
                                                        ?>
                                                    </div>
                                            </div>
                                        </div>
                                    <?endforeach?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row set-border-top">
                <div class="col-xl-6 bx-constructor-result-table-total">
                    <table class="bx-constructor-result-table">
                        <tr style="display: <?=($arResult['SHOW_DEFAULT_SET_DISCOUNT'] ? 'table-row' : 'none'); ?>;">
                            <td class="bx-constructor-result-table-title"><?=GetMessage("CATALOG_SET_PRODUCTS_PRICE")?>:</td>
                            <td class="bx-constructor-result-table-value">
                                <strong data-role="set-old-price"><?=$arResult["SET_ITEMS"]["OLD_PRICE"]?></strong>
                            </td>
                        </tr>
                        <tr>
                            <td class="bx-constructor-result-table-title"><?=GetMessage("CATALOG_SET_SET_PRICE")?>:</td>
                            <td class="bx-constructor-result-table-value">
                                <strong data-role="set-price"><?=$arResult["SET_ITEMS"]["PRICE"]?></strong>
                            </td>
                        </tr>
                        <tr style="display: <?=($arResult['SHOW_DEFAULT_SET_DISCOUNT'] ? 'table-row' : 'none'); ?>;">
                            <td class="bx-constructor-result-table-title"><?=GetMessage("CATALOG_SET_ECONOMY_PRICE")?>:</td>
                            <td class="bx-constructor-result-table-value">
                                <strong data-role="set-diff-price"><?=$arResult["SET_ITEMS"]["PRICE_DISCOUNT_DIFFERENCE"]?></strong>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-xl-6 bx-constructor-result-sum">
                    <div class="bx-constructor-result-btn-container bx-constructor-result-total-container">
                    <span class="bx-constructor-result-title-price">
                        <?=GetMessage("CATALOG_SET_TOTAL_PRICE")?>:
                    </span>
                    <span class="bx-constructor-result-price" data-role="set-price-duplicate">
                        <?=$arResult["SET_ITEMS"]["PRICE"]?>
                    </span>
                    </div>
                    <div class="bx-constructor-result-btn-container">
                        <a href="javascript:void(0)" data-role="set-buy-btn" class="set_buy_btn"
                            <?=($arResult["ELEMENT"]["CAN_BUY"] ? '' : 'style="display: none;"')?>>
                            <?=GetMessage("CATALOG_SET_BUY")?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
<?
$arJsParams = array(
	"numSliderItems" => count($arResult["SET_ITEMS"]["OTHER"]),
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
	"noFotoSrc" => $this->GetFolder().'/images/no_foto.png',
	"messages" => array(
		"EMPTY_SET" => GetMessage('CT_BCE_CATALOG_MESS_EMPTY_SET'),
		"ADD_BUTTON" => GetMessage("CATALOG_SET_BUTTON_ADD")
	)
);
?>
<script type="text/javascript">
	BX.ready(function(){
		new BX.Catalog.SetConstructor(<?=CUtil::PhpToJSObject($arJsParams, false, true, true)?>);
	});
</script>
