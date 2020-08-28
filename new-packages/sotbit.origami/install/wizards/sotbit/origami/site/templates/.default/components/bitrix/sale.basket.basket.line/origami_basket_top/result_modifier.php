<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main,
    Bitrix\Sale;
use Bitrix\Sale\Internals\BasketPropertyTable, Sotbit\Origami\Helper\Config;


Main\Loader::includeModule('sotbit.origami');

$arResult["SHOW_BASKET"] = Config::checkAction("BUY");
$arResult["SHOW_DELAY"] = Config::checkAction("DELAY");
$arResult["SHOW_COMPARE"] = Config::checkAction("COMPARE");

//Bitrix\Currency;

$arResult["NUM_PRODUCTS_COMPARE"] = 0;
$compare = new \Sotbit\Origami\Sale\Basket\Compare();
$arResult["NUM_PRODUCTS_COMPARE"] = $compare->getCompared();

$arBasketID = $arDelayID = $arCompareID = $arID = $arProductID = $basketItems = $arrayRatio = array();
$arDefault = array("CATALOG.XML_ID" => 1, "PRODUCT.XML_ID" => 1);
$arResult["NUM_PRODUCTS_DELAY"] = 0;
$arResult["ECONOM_ITOGO"] = 0;
$arResult["OLD_PRICE_ITOGO"] = 0;
$arResult["CURRENCY"] = CSaleLang::GetLangCurrency(SITE_ID);
$arResult["CURRENCY_FORMAT"] = (isset(CCurrencyLang::$arCurrencyFormat[$arResult["CURRENCY"]]) ? CCurrencyLang::$arCurrencyFormat[$arResult["CURRENCY"]] : CCurrencyLang::GetFormatDescription($arResult["CURRENCY"]));
$arResult["CURRENCY_FORMAT_STRING"] = str_replace("# ", "", $arResult["CURRENCY_FORMAT"]["FORMAT_STRING"]);
$arResult["TOTAL_PRICE_VALUE"] = str_replace($arResult["CURRENCY_FORMAT_STRING"], "", $arResult["TOTAL_PRICE"]);
$arParams["TAB_ACTIVE"] = isset($arParams["TAB_ACTIVE"]) ? $arParams["TAB_ACTIVE"] : "BUY";
$arResult["PROPS"] = array();

$Item = new \Sotbit\Origami\Image\Item();


// find products
foreach ($arResult['CATEGORIES'] as $CategoryCode => &$Category)
{
    foreach ($Category as &$arProduct)
    {

        $basketItems[$arProduct["PRODUCT_ID"]] = $arProduct;

        if(!$arProduct['PICTURE_SRC'])
        {

            $arProduct['PICTURE_SRC'] = $Item->getNoImageSmall();
        }

        if ($arProduct["DISCOUNT_VALUE"] > 0)
        {
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


$Basket = new \Sotbit\Origami\Image\Basket();
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

unset($arrayRatio);
unset($arID);
unset($arProductID);
unset($arBasketID);

