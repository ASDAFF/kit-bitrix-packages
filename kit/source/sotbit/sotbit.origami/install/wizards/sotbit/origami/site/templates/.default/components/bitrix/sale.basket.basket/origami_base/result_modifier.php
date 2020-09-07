<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
use Bitrix\Main;
use Sotbit\Origami\Helper\Config;

Main\Loader::includeModule('sotbit.origami');

$defaultParams = array(
	'TEMPLATE_THEME' => 'blue'
);
$arParams = array_merge($defaultParams, $arParams);
unset($defaultParams);

$arParams['TEMPLATE_THEME'] = (string)($arParams['TEMPLATE_THEME']);
if ('' != $arParams['TEMPLATE_THEME'])
{
	$arParams['TEMPLATE_THEME'] = preg_replace('/[^a-zA-Z0-9_\-\(\)\!]/', '', $arParams['TEMPLATE_THEME']);
	if ('site' == $arParams['TEMPLATE_THEME'])
	{
		$templateId = (string)Main\Config\Option::get('main', 'wizard_template_id', 'eshop_bootstrap', SITE_ID);
		$templateId = (preg_match("/^eshop_adapt/", $templateId)) ? 'eshop_adapt' : $templateId;
		$arParams['TEMPLATE_THEME'] = (string)Main\Config\Option::get('main', 'wizard_'.$templateId.'_theme_id', 'blue', SITE_ID);
	}
	if ('' != $arParams['TEMPLATE_THEME'])
	{
		if (!is_file($_SERVER['DOCUMENT_ROOT'].$this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css'))
			$arParams['TEMPLATE_THEME'] = '';
	}
}
if ('' == $arParams['TEMPLATE_THEME'])
	$arParams['TEMPLATE_THEME'] = 'blue';



//printr($arResult);

/*if($arResult["GRID"]["ROWS"])
{//die();
    $IBLOCK_ID = Config::get("IBLOCK_ID", SITE_ID);
    $arCatalog = \CCatalogSKU::GetInfoByProductIBlock($IBLOCK_ID);

    $SKU_IBLOCK_ID = $arCatalog["IBLOCK_ID"];
    $SKU_PROPERTY_ID = $arCatalog["SKU_PROPERTY_ID"];

    foreach($arResult["GRID"]["ROWS"] as &$arItem)
    {
        $ID = $arItem["ID"];
        $productID = $arItem["PRODUCT_ID"];

        $props = array();

        if(isset($arItem["PROPS"]))
        {
            foreach($arItem["PROPS"] as $code => $arPropVal)
            {
                if($arPropVal["CODE"] != "CATALOG.XML_ID" && $arPropVal["CODE"] != "PRODUCT.XML_ID")
                {
                    $props[] = $arPropVal["CODE"];
                }
            }
        }

        $arSkuFilter = array(
            "IBLOCK_ID" => $SKU_IBLOCK_ID,
            "ID" => $productID,
        );

        //printr($arSkuFilter);

        $rsElements = \CIBlockElement::GetList(array(), $arSkuFilter, false, Array("nTopCount" => 1), array("ID", "NAME", "PROPERTY_".$SKU_PROPERTY_ID, "PROPERTY_".$SKU_PROPERTY_ID.".NAME"));

        while($arSku = $rsElements->Fetch())
        {
            $parentID = $arSku["PROPERTY_".$SKU_PROPERTY_ID."_VALUE"];
            $productName = $arSku["NAME"];
            $parentName = $arSku["PROPERTY_".$SKU_PROPERTY_ID."_NAME"];

            if($parentID)
            {

                $tmpResult = [
                    'ID' => $parentID,
                    'NAME' => $parentName,
                    'OFFERS' => [
                        0 => [
                            'ID' => $productID,
                            'NAME' => $productName,
                        ]
                    ]
                ];

                $Offer = new \Sotbit\Origami\Helper\Offer(SITE_ID);
                $newName = $Offer->changeText($tmpResult, $props);
                $arItem["NAME"] = $newName;
                $arItem["~NAME"] = $newName;
                //$basketItem->setField('NAME', $newName);
                //printr(array("ID" => $ID));

                $resultBasket = \Bitrix\Sale\Internals\BasketTable::Update($ID, array("NAME" => $newName));

            }

            //printr($arItem);
        }



    }

}*/
