<?php
global $APPLICATION;
$APPLICATION->RestartBuffer();
use Bitrix\Main,
    Bitrix\Sale;
use Bitrix\Sale\Internals\BasketPropertyTable, Kit\Origami\Helper\Config;


Main\Loader::includeModule('kit.origami');

$arResult["SHOW_BASKET"] = Config::checkAction("BUY");
$arResult["SHOW_DELAY"] = Config::checkAction("DELAY");
$arResult["SHOW_COMPARE"] = Config::checkAction("COMPARE");

$arResult['LANG'] = array(
    'REACT_BASKET_NAME' => GetMessage('REACT_BASKET_NAME'),
    'REACT_IN_BASKET' => GetMessage('REACT_IN_BASKET'),
    'REACT_DELAY_PRODUCTS' => GetMessage('REACT_DELAY_PRODUCTS'),
    'REACT_CLEAR_PRODUCTS' => GetMessage('REACT_CLEAR_PRODUCTS'),
    'REACT_CONTINUE_PRODUCTS' => GetMessage('REACT_CONTINUE_PRODUCTS'),
    'REACT_TOTAL' => GetMessage('REACT_TOTAL'),
    'REACT_QUICK_ORDER' => GetMessage('REACT_QUICK_ORDER'),
    'REACT_GO_TO_BASKET' => GetMessage('REACT_GO_TO_BASKET'),
    'REACT_BASKET_EMPTY' => GetMessage('REACT_BASKET_EMPTY'),
    'REACT_BASKET_EMPTY_DESCRIPTION' => GetMessage('REACT_BASKET_EMPTY_DESCRIPTION'),
    'REACT_BASKET_EMPTY_LINK' => GetMessage('REACT_BASKET_EMPTY_LINK'),
    'REACT_BASKET_DELAY_EMPTY' => GetMessage('REACT_BASKET_DELAY_EMPTY'),
    'REACT_BASKET_DELAY_EMPTY_DESCRIPTION' => GetMessage('REACT_BASKET_DELAY_EMPTY_DESCRIPTION'),
    'REACT_BASKET_DELAY_EMPTY_LINK' => GetMessage('REACT_BASKET_DELAY_EMPTY_LINK'),
    'REACT_BASKET_COLUMN_NAME' => GetMessage('REACT_BASKET_COLUMN_NAME'),
    'REACT_BASKET_COLUMN_PRICE' => GetMessage('REACT_BASKET_COLUMN_PRICE'),
    'REACT_BASKET_COLUMN_COUNT' => GetMessage('REACT_BASKET_COLUMN_COUNT'),
    'REACT_BASKET_COLUMN_SUM' => GetMessage('REACT_BASKET_COLUMN_SUM'),
    'REACT_BASKET_FIELD_PRICE_BY' => GetMessage('REACT_BASKET_FIELD_PRICE_BY'),
    'REACT_BASKET_CHECKOUT_ORDER' => GetMessage('REACT_BASKET_CHECKOUT_ORDER'),
    'REACT_BASKET_DISCOUNT' => GetMessage('REACT_BASKET_DISCOUNT'),
    'REACT_BASKET_RETURN' => GetMessage('REACT_BASKET_RETURN'),
    'REACT_BASKET_PRODUCT_TITLE' => GetMessage('REACT_BASKET_PRODUCT_TITLE'),
    'REACT_BASKET_DELETED_FROM_CART' => GetMessage('REACT_BASKET_DELETED_FROM_CART'),
    'REACT_BASKET_DELETED_FROM_DELAY' => GetMessage('REACT_BASKET_DELETED_FROM_DELAY'),
    'REACT_BASKET_CLEAN_DELAY' => GetMessage('REACT_BASKET_CLEAN_DELAY'),
);

$arResult["NUM_PRODUCTS_COMPARE"] = 0;
$arParams['IMAGE_FOR_OFFER'] = "PRODUCT";
$compare = new \Kit\Origami\Sale\Basket\Compare();
$arResult["NUM_PRODUCTS_COMPARE"] = $compare->getCompared();

$arBasketID = $arDelayID = $arCompareID = $arID = $arProductID = $basketItems = $arrayRatio = array();
$arDefault = array("CATALOG.XML_ID" => 1, "PRODUCT.XML_ID" => 1);
$arResult["NUM_PRODUCTS_DELAY"] = 0;
$arResult["ECONOM_ITOGO"] = 0;
$arResult["OLD_PRICE_ITOGO"] = 0;
$arResult["CURRENCY"] = CSaleLang::GetLangCurrency(SITE_ID);
$arResult["CURRENCY_FORMAT"] = CCurrencyLang::GetFormatDescription($arResult["CURRENCY"]);
$arResult["CURRENCY_FORMAT_STRING"] = str_replace("# ", "", $arResult["CURRENCY_FORMAT"]["FORMAT_STRING"]);
$arResult["TOTAL_PRICE_VALUE"] = str_replace($arResult["CURRENCY_FORMAT_STRING"], "", $arResult["TOTAL_PRICE"]);
$arParams["TAB_ACTIVE"] = isset($arParams["TAB_ACTIVE"]) ? $arParams["TAB_ACTIVE"] : "BUY";
$arResult["PROPS"] = array();

$Item = new \Kit\Origami\Image\Item();

$res = CIBlockSection::GetByID(Config::get('IBLOCK_ID'));
$ar_res = $res->GetNext();
$catalog = CIBlock::GetList(array(), array('CODE' => 'kit_origami_catalog'), true);

$arResult['CATALOG_PATH'] = str_replace('#SITE_DIR#/', '', $catalog->Fetch()['LIST_PAGE_URL']); //$ar_res['LIST_PAGE_URL'];
$arResult['BASKET_PATH'] = Config::get('BASKET_PAGE');
if(Config::get("HEADER_CALL") == "Y" && \Bitrix\Main\Loader::includeModule('kit.orderphone')) {
    $arResult['ORDER_PHONE_ACTIVE'] = true;
} else {
    $arResult['ORDER_PHONE_ACTIVE'] = false;
}
// find products
$arResult["OLD_PRICE_ITOGO"] = 0;

foreach ($arResult['CATEGORIES'] as $CategoryCode => &$Category)
{
    foreach ($Category as &$arProduct)
    {

        $econom = $arProduct["BASE_PRICE"] - $arProduct["PRICE"];
        $economSum = $econom * $arProduct["QUANTITY"];
        $oldPrice = $arProduct["BASE_PRICE"] * $arProduct["QUANTITY"];

        $arResult["ECONOM_ITOGO_TOTAL"] = $arResult["ECONOM_ITOGO"] + $economSum;
        $arResult["OLD_PRICE_ITOGO_TOTAL"] = $arResult["OLD_PRICE_ITOGO_TOTAL"] + $oldPrice;
    }
}

foreach ($arResult['CATEGORIES'] as $CategoryCode => &$Category)
{
    foreach ($Category as &$arProduct)
    {
        $basketItems[$arProduct["PRODUCT_ID"]] = $arProduct;
        if(!$arProduct['PICTURE_SRC'])
        {
            $arProduct['PICTURE_SRC'] = $Item->getNoImageSmall();
        }

        if ($arProduct["DISCOUNT_PRICE"] > 0) {
        $econom = $arProduct["BASE_PRICE"] - $arProduct["PRICE"];
        $economSum = $econom * $arProduct["QUANTITY"];
        $oldPrice = $arProduct["BASE_PRICE"] * $arProduct["QUANTITY"];

        $arResult["ECONOM_ITOGO"] = $arResult["ECONOM_ITOGO"] + $economSum;
        $arResult["OLD_PRICE_ITOGO"] = $arResult["OLD_PRICE_ITOGO"] + $oldPrice;

        $arProduct["ECONOM"] = Sale\PriceMaths::roundPrecision($econom);
        $arProduct["ECONOM_SUM"] = Sale\PriceMaths::roundPrecision($economSum);
        $arProduct["OLD_PRICE"] = Sale\PriceMaths::roundPrecision($oldPrice);
        $arProduct["ECONOM_FORMAT"] = \CCurrencyLang::CurrencyFormat($arProduct["ECONOM"], $arProduct['CURRENCY'], true);
        $arProduct["ECONOM_SUM_FORMAT"] = \CCurrencyLang::CurrencyFormat($arProduct["ECONOM_SUM"], $arProduct['CURRENCY'], true);
        $arProduct["OLD_PRICE_FORMAT"] = \CCurrencyLang::CurrencyFormat($arProduct["OLD_PRICE"], $arProduct['CURRENCY'], true);
    }

        $arProduct["NAME"] = htmlspecialcharsBack($arProduct["NAME"]);

        $rsAvailable = CCatalogProduct::GetList(array(), array('ID' => $arProduct['PRODUCT_ID']));
        while ($it = $rsAvailable->fetch()) {
            $arProduct['AVAILABLE_QUANTITY'] = $it['QUANTITY'];
            $arProduct['QUANTITY_TRACE'] = $it['QUANTITY_TRACE'];
        }

        if ($arProduct["DELAY"] == "Y")
        {
            $arResult["NUM_PRODUCTS_DELAY"]++;
        }

        if($arProduct["DELAY"] == "N" && $arProduct["CAN_BUY"] == "Y")
        {
            $arBasketID[] = $arProduct["PRODUCT_ID"];
        }

        if($arProduct["DELAY"] == "Y")
        {
            $arDelayID[] = $arProduct["PRODUCT_ID"];
        }


        $arID[] = $arProduct["ID"];

        $arProductID[] = $arProduct["PRODUCT_ID"];

    }
}

if(count($arBasketID)>0)
{
    $arResult["arBasketID"] = array_values(array_unique($arBasketID));
}

if(count($arDelayID)>0)
{
    $arResult["arDelayID"] = array_values(array_unique($arDelayID));
}

if($arResult["NUM_PRODUCTS_COMPARE"] > 0)
{
    $arResult["arCompareID"] = $compare->getCompareItems();
}

if($arResult["ECONOM_ITOGO"]>0)
{
    $arResult["ECONOM_ITOGO_FORMAT"] = \CCurrencyLang::CurrencyFormat($arResult["ECONOM_ITOGO"], $arResult["CURRENCY"], true);
    $arResult["OLD_PRICE_ITOGO_FORMAT"] = \CCurrencyLang::CurrencyFormat($arResult["OLD_PRICE_ITOGO"], $arResult["CURRENCY"], true);
}

if ($arResult["OLD_PRICE_ITOGO_TOTAL"]) {
    $arResult["OLD_PRICE_ITOGO_TOTAL"] = \CCurrencyLang::CurrencyFormat( $arResult["OLD_PRICE_ITOGO_TOTAL"], $arResult["CURRENCY"], true);
}

$Basket = new \Kit\Origami\Image\Basket();
$Basket->setMediumHeight(70);
$Basket->setMediumWidth(70);
$images = [];

if ($arParams['IMAGE_FOR_OFFER'] == 'PRODUCT') {
    $images = $Basket->getImages($arProductID);
}

// find props from products
if (!empty($arID))
{
    $res = Bitrix\Sale\Internals\BasketPropertyTable::getList([
        'order' => [
            "SORT" => "ASC",
            "ID" => "ASC"
        ],
        'filter' => [
            "BASKET_ID" => $arID,
        ],
    ]);

    while ($property = $res->fetch())
    {
        if (!isset($arDefault[$property["CODE"]]))
        {
            $arResult["PROPS"][$property["BASKET_ID"]][$property["CODE"]] = $property;
        }
    }

    $ratio = \Bitrix\Catalog\MeasureRatioTable::getList(
        array(
            'select' => array('*'),
            'filter' => array('@PRODUCT_ID' => $arProductID, '=IS_DEFAULT' => 'Y')
        )
    );

    while($arRatio = $ratio->fetch())
    {
        $arrayRatio[$arRatio["PRODUCT_ID"]] = $arRatio["RATIO"];
    }

}

$arResult["NUM_PRODUCTS_ALL"] = $arResult["NUM_PRODUCTS"] + $arResult["NUM_PRODUCTS_DELAY"];

foreach( $arResult['CATEGORIES'] as $CategoryCode1 =>  $Category1 )
{
    foreach ($Category1 as $i => &$Product)
    {
        $arResult['CATEGORIES'][$CategoryCode1][$i]["RATIO"] = $arrayRatio[$Product["PRODUCT_ID"]];

        if ($images[$Product['PRODUCT_ID']])
        {
            $Product = $Basket->changeImages($Product, $images[$Product['PRODUCT_ID']]);
            $arResult['CATEGORIES'][$CategoryCode1][$i]["PICTURE_SRC"] = $Product['PREVIEW_PICTURE_SRC'];
        }
    }
}

foreach ($arResult['CATEGORIES'] as &$parentCategory) {
    if(is_array($parentCategory)) {
        foreach ($parentCategory as &$item) {
            $ratio = \Bitrix\Catalog\MeasureRatioTable::getList(
                array(
                    'select' => array('RATIO', 'PRODUCT_ID'),
                    'filter' => array('PRODUCT_ID' => $item['PRODUCT_ID'])
                )
            )->Fetch();
            $item['RATIO'] = $ratio['RATIO'];
            $item['MAX_QUANTITY'] = CCatalogProduct::GetByID($item['PRODUCT_ID'])['QUANTITY'];
            ($item['MAX_QUANTITY'] == 0) ? $item['MAX_QUANTITY'] = null : $item['MAX_QUANTITY'];
        }
    }
}

foreach ($arResult['CATEGORIES'] as &$parentCategory) {
    foreach ($parentCategory as &$item) {
        foreach ($arResult['PROPS'] as $keyProp => $arProp) {
            if ((int)$item['ID'] == (int)$keyProp) {
                $item['PROPS'] = $arProp;
                $itProps = $item['PROPS'];
                unset($item['PROPS']);
                foreach ($itProps as $prop) {
                    $item['PROPS'][] = [
                        'ID' => $prop['ID'],
                        'NAME' => $prop['NAME'],
                        'VALUE' => $prop['VALUE']
                    ];
                }
                unset($itProps);
            }
        }
    }
}

unset($arrayRatio);
unset($arID);
unset($arProductID);
unset($arBasketID);
