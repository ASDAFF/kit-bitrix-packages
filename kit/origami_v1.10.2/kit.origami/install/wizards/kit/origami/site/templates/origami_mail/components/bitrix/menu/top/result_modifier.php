<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
foreach($arResult as $i=>$arItem)
{
	if($arItem['DEPTH_LEVEL']>1 || !$arItem['PARAMS']['FROM_IBLOCK'])
		unset($arResult[$i]);
}
$arResult = array_values($arResult);
?>