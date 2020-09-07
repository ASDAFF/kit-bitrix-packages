<?
//Navigation chain template
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;
use intec\core\helpers\ArrayHelper;

$arChainBody = array();

$arInsert = [];
$arInsert[] = [
    'TITLE' => Loc::getMessage('C_SEARCH_PAGE_TEMPLATE_1_MAIN'),
    'LINK' => SITE_DIR
];
$arCHAIN = ArrayHelper::merge($arInsert, $arCHAIN);

foreach($arCHAIN as $item)
{
	if(strlen($item["LINK"])<strlen(SITE_DIR))
		continue;
	if($item["LINK"] <> "")
		$arChainBody[] = '<a class="intec-cl-text-hover" href="'.$item["LINK"].'">'.htmlspecialcharsex($item["TITLE"]).'</a>';
	else
		$arChainBody[] = htmlspecialcharsex($item["TITLE"]);
}
return implode('&nbsp;/&nbsp;', $arChainBody);
?>