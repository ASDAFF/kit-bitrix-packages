<?php
define("STOP_STATISTICS", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Sale;

if(\Bitrix\Main\Loader::includeModule("sale")){echo 5;
    $basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), $siteId);
    foreach ($basket as $basketItem) {
        $basketItem->delete();
    }
    $basket->save();
}