<?
use Kit\Origami\Helper\Config;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
try {
    \Bitrix\Main\Loader::includeModule('kit.origami');
} catch (\Bitrix\Main\LoaderException $e) {
    print_r($e->getMessage());
}

$arItem = $arResult["ITEM"];
$img = isset($arItem["PREVIEW_PICTURE"]["ID"]) ? $arItem["PREVIEW_PICTURE"]["ID"] : $arItem["DETAIL_PICTURE"]["ID"];

$obImg = new \Kit\Origami\Image\Item();

if($img)
{
    $arFile = $obImg->resize($img,['width' => 70, 'height' => 70], BX_RESIZE_IMAGE_PROPORTIONAL);
    $arResult["ITEM"]["PICT"] = $arFile;
}else{
    $arResult["ITEM"]["PICT"]["SRC"] = $obImg->getNoImageSmall();
}
?>