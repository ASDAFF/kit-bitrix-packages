<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use intec\core\helpers\ArrayHelper;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */

$arParams = ArrayHelper::merge([
    'SETTINGS_USE' => 'N',
    'LAZYLOAD_USE' => 'N'
], $arParams);

if ($arParams['SETTINGS_USE'] === 'Y')
    include(__DIR__.'/modifiers/settings.php');

$arVisual = ArrayHelper::merge($arResult['VISUAL'], [
    'LAZYLOAD' => [
        'USE' => $arParams['LAZYLOAD_USE'] === 'Y',
        'STUB' => null
    ]
]);

if (defined('EDITOR'))
    $arVisual['LAZYLOAD']['USE'] = false;

if ($arVisual['LAZYLOAD']['USE'])
    $arVisual['LAZYLOAD']['STUB'] = Properties::get('template-images-lazyload-stub');

$arResult['VISUAL'] = $arVisual;

unset($arVisual);

$arResult["TAGS_CHAIN"] = array();
if($arResult["REQUEST"]["~TAGS"])
{
	$res = array_unique(explode(",", $arResult["REQUEST"]["~TAGS"]));
	$url = array();
	foreach ($res as $key => $tags)
	{
		$tags = trim($tags);
		if(!empty($tags))
		{
			$url_without = $res;
			unset($url_without[$key]);
			$url[$tags] = $tags;
			$result = array(
				"TAG_NAME" => htmlspecialcharsex($tags),
				"TAG_PATH" => $APPLICATION->GetCurPageParam("tags=".urlencode(implode(",", $url)), array("tags")),
				"TAG_WITHOUT" => $APPLICATION->GetCurPageParam((count($url_without) > 0 ? "tags=".urlencode(implode(",", $url_without)) : ""), array("tags")),
			);
			$arResult["TAGS_CHAIN"][] = $result;
		}
	}
}
if (!empty($arResult["SEARCH"])) {
    $arItemsIDFilter = array();
    $arItemsID = array();
    foreach($arResult["SEARCH"] as $keyItem=>$arItem) {
        $arItemsIDFilter[] = $arItem['ITEM_ID'];
        $arItemsID[$arItem['ITEM_ID']]['ID'] = $arItem['ITEM_ID'];
        $arItemsID[$arItem['ITEM_ID']]['KEY'] = $keyItem;
    }

    CModule::IncludeModule('iblock');
    $dbItems = CIBlockElement::GetList(array(), array('ID'=> $arItemsIDFilter), false, false, array('ID', 'DETAIL_PICTURE', 'PREVIEW_PICTURE'));

    while ($arElement = $dbItems->Fetch()) {
        if (!empty($arElement['PREVIEW_PICTURE'])) {
            $picture = CFile::ResizeImageGet($arElement['PREVIEW_PICTURE'], array('width' => 270, 'height' => 270, BX_RESIZE_IMAGE_PROPORTIONAL_ALT));

            $arResult["SEARCH"][$arItemsID[$arElement['ID']]['KEY']]['PICTURE'] = $picture['src'];
        } else if (!empty($arElement['DETAIL_PICTURE'])) {
            $picture = CFile::ResizeImageGet($arElement['DETAIL_PICTURE'], array('width' => 270, 'height' => 270, BX_RESIZE_IMAGE_PROPORTIONAL_ALT));

            $arResult["SEARCH"][$arItemsID[$arElement['ID']]['KEY']]['PICTURE'] = $picture['src'];
        }
    }
}
?>