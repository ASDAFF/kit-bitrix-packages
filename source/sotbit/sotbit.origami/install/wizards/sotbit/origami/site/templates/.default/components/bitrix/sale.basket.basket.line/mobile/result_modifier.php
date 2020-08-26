<?
if(!defined( "B_PROLOG_INCLUDED" )||B_PROLOG_INCLUDED!==true) die();

$ProductsId = array();
$Offers = array();
$arResult["PROPS"] = array();
$arResult['COST'] = 0;
$arResult['QNT'] = 0;
$currency = "RUB";
$BasketIds = array();
$ProductIds = array();
$OffersIds = array();
$codeMorePhoto = "MORE_PHOTO";
// find products
foreach( $arResult['CATEGORIES'] as $CategoryCode => $Category )
{
    foreach( $Category as $Product )
    {
        if($Product["CAN_BUY"]=="Y"&&$Product["DELAY"]=="N"&&$Product['SUBSCRIBE']=="N")
        {
            if($Product['CAN_BUY'])
            {
                $arResult['QNT'] += $Product['QUANTITY'];
                $arResult['COST'] += $Product['PRICE']*$Product['QUANTITY'];
                $currency = $Product['CURRENCY'];
                $OffersIds[] = $Product['PRODUCT_ID'];
                $BasketIds[] = $Product['ID'];
                $arResult['NAMES'][$Product['PRODUCT_ID']]=$Product['NAME'];
            }
        }
    }
}