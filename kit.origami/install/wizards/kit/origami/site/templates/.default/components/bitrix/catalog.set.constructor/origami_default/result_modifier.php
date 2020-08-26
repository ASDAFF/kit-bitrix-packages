<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arDefaultParams = array(
	'TEMPLATE_THEME' => 'blue',
);
$arParams = array_merge($arDefaultParams, $arParams);

$arParams['TEMPLATE_THEME'] = (string)($arParams['TEMPLATE_THEME']);
if ('' != $arParams['TEMPLATE_THEME'])
{
	$arParams['TEMPLATE_THEME'] = preg_replace('/[^a-zA-Z0-9_\-\(\)\!]/', '', $arParams['TEMPLATE_THEME']);
	if ('site' == $arParams['TEMPLATE_THEME'])
	{
		$templateId = COption::GetOptionString("main", "wizard_template_id", "eshop_bootstrap", SITE_ID);
		$templateId = (preg_match("/^eshop_adapt/", $templateId)) ? "eshop_adapt" : $templateId;
		$arParams['TEMPLATE_THEME'] = COption::GetOptionString('main', 'wizard_'.$templateId.'_theme_id', 'blue', SITE_ID);
	}
	if ('' != $arParams['TEMPLATE_THEME'])
	{
		if (!is_file($_SERVER['DOCUMENT_ROOT'].$this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css'))
			$arParams['TEMPLATE_THEME'] = '';
	}
}
if ('' == $arParams['TEMPLATE_THEME'])
	$arParams['TEMPLATE_THEME'] = 'blue';

if ($arResult["ELEMENT"]['DETAIL_PICTURE'] || $arResult["ELEMENT"]['PREVIEW_PICTURE'])
{
	$arFileTmp = CFile::ResizeImageGet(
		$arResult["ELEMENT"]['DETAIL_PICTURE'] ? $arResult["ELEMENT"]['DETAIL_PICTURE'] : $arResult["ELEMENT"]['PREVIEW_PICTURE'],
		array("width" => "150", "height" => "180"),
		BX_RESIZE_IMAGE_PROPORTIONAL,
		true
	);
	$arResult["ELEMENT"]['DETAIL_PICTURE'] = $arFileTmp;
}

$needUrl = [];


$ipropElementValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arResult['ELEMENT']['IBLOCK_ID'],$arResult['ELEMENT']['ID']);
$arResult['ELEMENT']['SEO'] = $ipropElementValues->getValues();

$arDefaultSetIDs = array($arResult["ELEMENT"]["ID"]);
foreach (array("DEFAULT", "OTHER") as $type)
{
	foreach ($arResult["SET_ITEMS"][$type] as $key=>$arItem)
	{
		$arElement = array(
			"ID"=>$arItem["ID"],
			"NAME" =>$arItem["NAME"],
			"DETAIL_PAGE_URL"=>$arItem["DETAIL_PAGE_URL"],
			"DETAIL_PICTURE"=>$arItem["DETAIL_PICTURE"],
			"PREVIEW_PICTURE"=> $arItem["PREVIEW_PICTURE"],
			"PRICE_CURRENCY" => $arItem["PRICE_CURRENCY"],
			"PRICE_DISCOUNT_VALUE" => $arItem["PRICE_DISCOUNT_VALUE"],
			"PRICE_PRINT_DISCOUNT_VALUE" => $arItem["PRICE_PRINT_DISCOUNT_VALUE"],
			"PRICE_VALUE" => $arItem["PRICE_VALUE"],
			"PRICE_PRINT_VALUE" => $arItem["PRICE_PRINT_VALUE"],
			"PRICE_DISCOUNT_DIFFERENCE_VALUE" => $arItem["PRICE_DISCOUNT_DIFFERENCE_VALUE"],
			"PRICE_DISCOUNT_DIFFERENCE" => $arItem["PRICE_DISCOUNT_DIFFERENCE"],
			"CAN_BUY" => $arItem['CAN_BUY'],
			"SET_QUANTITY" => $arItem['SET_QUANTITY'],
			"MEASURE_RATIO" => $arItem['MEASURE_RATIO'],
			"BASKET_QUANTITY" => $arItem['BASKET_QUANTITY'],
			"MEASURE" => $arItem['MEASURE']
		);
		if ($arItem["PRICE_CONVERT_DISCOUNT_VALUE"])
			$arElement["PRICE_CONVERT_DISCOUNT_VALUE"] = $arItem["PRICE_CONVERT_DISCOUNT_VALUE"];
		if ($arItem["PRICE_CONVERT_VALUE"])
			$arElement["PRICE_CONVERT_VALUE"] = $arItem["PRICE_CONVERT_VALUE"];
		if ($arItem["PRICE_CONVERT_DISCOUNT_DIFFERENCE_VALUE"])
			$arElement["PRICE_CONVERT_DISCOUNT_DIFFERENCE_VALUE"] = $arItem["PRICE_CONVERT_DISCOUNT_DIFFERENCE_VALUE"];

		if ($type == "DEFAULT")
			$arDefaultSetIDs[] = $arItem["ID"];
		if ($arItem['DETAIL_PICTURE'] || $arItem['PREVIEW_PICTURE'])
		{
			$arFileTmp = CFile::ResizeImageGet(
				$arItem['DETAIL_PICTURE'] ? $arItem['DETAIL_PICTURE'] : $arItem['PREVIEW_PICTURE'],
				array("width" => "150", "height" => "180"),
				BX_RESIZE_IMAGE_PROPORTIONAL,
				true
			);
			$arElement['DETAIL_PICTURE'] = $arFileTmp;
		}

        $ipropElementValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arItem['IBLOCK_ID'],$arItem['ID']);
        $arElement['SEO'] = $ipropElementValues->getValues();

		$arResult["SET_ITEMS"][$type][$key] = $arElement;

		if(!$arItem['DETAIL_PAGE_URL']){
		    $sku = \CCatalogSku::GetProductInfo($arItem['ID'],$arItem['IBLOCK_ID']);
		    if($sku['ID']){
                $needUrl[$arItem['ID']] = $sku['ID'];
            }
        }
	}
}

$arResult["DEFAULT_SET_IDS"] = $arDefaultSetIDs;
if($needUrl){
    $rs = CIBlockElement::GetList(
        [],
        ['ID' => $needUrl],
        false,
        false,
        ['ID','IBLOCK_ID','DETAIL_PAGE_URL']
    );
    while($el = $rs->GetNextElement()){
        $fields = $el->GetFields();
        foreach (array("DEFAULT", "OTHER") as $type) {
            foreach ($arResult["SET_ITEMS"][$type] as $key => &$arItem) {
                if(!$arItem['DETAIL_PAGE_URL'] && $needUrl[$arItem['ID']]){
                    foreach($needUrl as $idPr){
                        if($idPr == $fields['ID']){
                            $arItem['DETAIL_PAGE_URL'] = $fields['DETAIL_PAGE_URL'];
                            break;
                        }
                    }
                }
            }
        }
    }
}

$arResult['PRICES'] = [];
$arResult['PRICES'][$arResult['ELEMENT']['ID']]['CURRENCY'] = $arResult['ELEMENT']['PRICE_CURRENCY'];
$arResult['PRICES'][$arResult['ELEMENT']['ID']]['PRICE'] = $arResult['ELEMENT']['PRICE_DISCOUNT_VALUE'];
$arResult['PRICES'][$arResult['ELEMENT']['ID']]['BASE_PRICE'] = $arResult['ELEMENT']['PRICE_VALUE'];
$arResult['PRICES'][$arResult['ELEMENT']['ID']]['DISCOUNT'] = $arResult['ELEMENT']['PRICE_DISCOUNT_DIFFERENCE_VALUE'];
$arResult['PRICES'][$arResult['ELEMENT']['ID']]['PERSENT'] = $arResult['ELEMENT']['PRICE_DISCOUNT_PERCENT'];
if($arResult['SET_ITEMS']['DEFAULT']){
    foreach($arResult['SET_ITEMS']['DEFAULT'] as $item){
        $arResult['PRICES'][$item['ID']]['CURRENCY'] = $item['PRICE_CURRENCY'];
        $arResult['PRICES'][$item['ID']]['PRICE'] = $item['PRICE_DISCOUNT_VALUE'];
        $arResult['PRICES'][$item['ID']]['BASE_PRICE'] = $item['PRICE_VALUE'];
        $arResult['PRICES'][$item['ID']]['DISCOUNT'] = $item['PRICE_DISCOUNT_DIFFERENCE_VALUE'];
        $arResult['PRICES'][$item['ID']]['PERSENT'] = $item['PRICE_DISCOUNT_PERCENT'];
    }
}