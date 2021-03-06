<?
/**
 * Copyright (c) 27/8/2020 Created By/Edited By ASDAFF asdaff.asad@yandex.ru
 */

use Kit\Origami\Helper\Config;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
try {
    \Bitrix\Main\Loader::includeModule('kit.origami');
} catch (\Bitrix\Main\LoaderException $e) {
    print_r($e->getMessage());
}

$showSkuBlock = $arParams['PRODUCT_DISPLAY_MODE'] === 'Y';

if($showSkuBlock && (!isset($arResult['TEMPLATE']) || $arResult['TEMPLATE'] == "template_1" || $arResult['TEMPLATE'] == "template_2" || $arResult['TEMPLATE'] == "template_3" || $arResult['TYPE'] == "list"))
{
    if (!$arResult['ITEM']['MORE_PHOTO'] && $arResult['ITEM']['PROPERTIES']['MORE_PHOTO']['VALUE'])
    {
        $arResult['ITEM']['MORE_PHOTO'] = [];
        $rs = CFile::GetList(
            [],
            [
                '@ID' => implode(',',
                    $arResult['ITEM']['PROPERTIES']['MORE_PHOTO']['VALUE']),
            ]
        );
        while ($file = $rs->Fetch())
        {
            $file['SRC'] = CFile::GetFileSRC($file);
            $arResult['ITEM']['MORE_PHOTO'][] = $file;
        }
    }

    $arResult['ITEM'] = \KitOrigami::changeColorImages($arResult['ITEM'], 'preview');

    $Item = new \Kit\Origami\Image\Item();
    $arResult['ITEM'] = $Item->prepareImages($arResult['ITEM']);

    $color = \Kit\Origami\Helper\Color::getInstance(SITE_ID);
    $arParams = $color::changePropColorView($arResult, $arParams)['PARAMS'];
}else{
    $arResult['ITEM'] = \KitOrigami::changeColorImages($arResult['ITEM'], 'preview', false);
    $Item = new \Kit\Origami\Image\Item();
    $arResult['ITEM'] = $Item->prepareImages($arResult['ITEM']);
}

\KitOrigami::checkPriceDiscount($arResult['ITEM']);

if (Bitrix\Main\Loader::includeModule("kit.price")) {
    //$arResult['ITEM'] = KitPrice::ChangeMinPrice($arResult['ITEM']);
}
if (Bitrix\Main\Loader::includeModule("kit.regions")) {
    //$arResult['ITEM'] = \Kit\Regions\Sale\Price::change($arResult['ITEM']);
}
?>