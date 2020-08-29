<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if(!CModule::IncludeModule("catalog"))
	return;

if(COption::GetOptionString("kit.origami", "wizard_installed", "N", WIZARD_SITE_ID) == "Y" && !WIZARD_INSTALL_DEMO_DATA)
	return;

//offers iblock import
$shopLocalization = $wizard->GetVar("shopLocalization");

$iblockXMLFileOffers = WIZARD_SERVICE_RELATIVE_PATH."/xml/".LANGUAGE_ID."/offer.xml";

/*if ($shopLocalization == "ua")
    $iblockXMLFilePricesOffers = "/bitrix/modules/bitrix.eshop/install/public/xml/".LANGUAGE_ID."/catalog_prices_sku_ua.xml";
elseif ($shopLocalization == "bl")
    $iblockXMLFilePricesOffers = "/bitrix/modules/bitrix.eshop/install/public/xml/".LANGUAGE_ID."/catalog_prices_sku_bl.xml";
else
    $iblockXMLFilePricesOffers = "/bitrix/modules/bitrix.eshop/install/public/xml/".LANGUAGE_ID."/catalog_prices_sku.xml";*/

$iblockCodeOffers = "kit_origami_offers_"  . WIZARD_SITE_ID;
$iblockTypeOffers = "kit_origami_catalog";
//$iblockXMLID = 'offers_origami';
$iblockXMLID = 'kit_origami_offers_'  . WIZARD_SITE_ID;

$rsIBlock = CIBlock::GetList(array(), array("XML_ID" => $iblockXMLID, "TYPE" => $iblockTypeOffers));
$IBLOCK_OFFERS_ID = false;
if ($arIBlock = $rsIBlock->Fetch())
{
    $IBLOCK_OFFERS_ID = $arIBlock["ID"];
    if (WIZARD_INSTALL_DEMO_DATA)
    {
        $oldSites2 = [];
        $rs = CIblock::GetSite($arIBlock["ID"]);
        while($site = $rs->Fetch())
        {
            $oldSites2[] = $site['SITE_ID'];
        }
        CIBlock::Delete($arIBlock["ID"]);
        $IBLOCK_OFFERS_ID = false;
    }
}
//--offers

if($IBLOCK_OFFERS_ID == false)
{
    $permissions = Array(
        "1" => "X",
        "2" => "R"
    );
    $dbGroup = CGroup::GetList($by = "", $order = "", Array("STRING_ID" => "sale_administrator"));
    if($arGroup = $dbGroup -> Fetch())
    {
        $permissions[$arGroup["ID"]] = 'W';
    }
    $dbGroup = CGroup::GetList($by = "", $order = "", Array("STRING_ID" => "content_editor"));
    if($arGroup = $dbGroup -> Fetch())
    {
        $permissions[$arGroup["ID"]] = 'W';
    }

    \Bitrix\Catalog\Product\Sku::disableUpdateAvailable();


    $sites = [WIZARD_SITE_ID];

    if($oldSites2 && is_array($oldSites2))
    {
        $sites = array_merge($sites,$oldSites2);
    }

    $IBLOCK_OFFERS_ID = WizardServices::ImportIBlockFromXML(
        $iblockXMLFileOffers,
        $iblockCodeOffers,
        $iblockTypeOffers,
        $sites,
        $permissions
    );

    \Bitrix\Catalog\Product\Sku::enableUpdateAvailable();

    if ($IBLOCK_OFFERS_ID < 1)
        return;

    $_SESSION["WIZARD_OFFERS_IBLOCK_ID"] = $IBLOCK_OFFERS_ID;

    $iblock = new CIBlock;
    $arFields = array(
        "CODE" => $iblockCodeOffers,
        "XML_ID" => $iblockXMLID,
    );

    $iblock->Update($IBLOCK_OFFERS_ID, $arFields);
}
else {
    $arSites = array();
    $db_res = CIBlock::GetSite($IBLOCK_OFFERS_ID);
    while ($res = $db_res->Fetch())
        $arSites[] = $res["LID"];
    if (!in_array(WIZARD_SITE_ID, $arSites)) {
        $arSites[] = WIZARD_SITE_ID;
        $iblock = new CIBlock;
        $iblock->Update($IBLOCK_OFFERS_ID, array(
                "LID" => $arSites,
                "CODE" => $iblockCodeOffers,
                "XML_ID" => $iblockXMLID,
            )
        );
    }
}

$arP = \Bitrix\Iblock\PropertyTable::getList(array('filter' => ['IBLOCK_ID' => $IBLOCK_OFFERS_ID],'select' => ['ID', 'CODE', 'NAME']))->fetchAll();

$arP = array_column($arP, null, 'CODE');

CUserOptions::SetOption(
    'form',
    'form_element_'.$IBLOCK_OFFERS_ID,
    array (
        'tabs' => 'edit1--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_edi1').'--,--ID--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_ID').'--,--DATE_CREATE--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_DATE_CREATE').'--,--TIMESTAMP_X--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_TIMESTAMP_X').'--,--ACTIVE--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_ACTIVE').'--,--ACTIVE_FROM--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_ACTIVE_FROM').'--,--ACTIVE_TO--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_ACTIVE_TO').'--,--NAME--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_NAME').'--,--CODE--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_CODE').'--,--SORT--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_SORT').'--,--IBLOCK_ELEMENT_PROPERTY--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_IBLOCK_ELEMENT_PROPERTY').'--,--PREVIEW_PICTURE--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_PREVIEW_PICTURE').'--,--DETAIL_PICTURE--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_DETAIL_PICTURE').'--,--PROPERTY_'.$arP['MORE_PHOTO']['ID'].'--#--'.$arP['MORE_PHOTO']['NAME'].'--,--PREVIEW_TEXT--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_PREVIEW_TEXT').'--,--DETAIL_TEXT--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_DETAIL_TEXT').'--,--IBLOCK_ELEMENT_PROP_VALUE--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_IBLOCK_ELEMENT_PROP_VALUE').'--,--PROPERTY_'.$arP['PROTSESSOR']['ID'].'--#--'.$arP['PROTSESSOR']['NAME'].'--,--PROPERTY_'.$arP['RAZMER']['ID'].'--#--'.$arP['RAZMER']['NAME'].'--,--PROPERTY_'.$arP['TSVET']['ID'].'--#--'.$arP['TSVET']['NAME'].'--,--PROPERTY_'.$arP['VIDEOKARTA']['ID'].'--#--'.$arP['VIDEOKARTA']['NAME'].'--,--PROPERTY_'.$arP['CHASTOTA_PROTSESSORA']['ID'].'--#--'.$arP['CHASTOTA_PROTSESSORA']['NAME'].'--,--PROPERTY_'.$arP['OBEM_OPERATIVNOY_PAMYATI']['ID'].'--#--'.$arP['OBEM_OPERATIVNOY_PAMYATI']['NAME'].'--,--PROPERTY_'.$arP['CML2_ARTICLE']['ID'].'--#--'.$arP['CML2_ARTICLE']['NAME'].'--,--PROPERTY_'.$arP['CML2_BASE_UNIT']['ID'].'--#--'.$arP['CML2_BASE_UNIT']['NAME'].'--,--PROPERTY_'.$arP['CML2_MANUFACTURER']['ID'].'--#--'.$arP['CML2_MANUFACTURER']['NAME'].'--,--PROPERTY_'.$arP['CML2_TRAITS']['ID'].'--#--'.$arP['CML2_TRAITS']['NAME'].'--,--PROPERTY_'.$arP['CML2_TAXES']['ID'].'--#--'.$arP['CML2_TAXES']['NAME'].'--,--PROPERTY_'.$arP['FILES']['ID'].'--#--'.$arP['FILES']['NAME'].'--,--PROPERTY_'.$arP['CML2_ATTRIBUTES']['ID'].'--#--'.$arP['CML2_ATTRIBUTES']['NAME'].'--,--PROPERTY_'.$arP['CML2_BAR_CODE']['ID'].'--#--'.$arP['CML2_BAR_CODE']['NAME'].'--,--PROPERTY_'.$arP['CML2_LINK']['ID'].'--#--'.$arP['CML2_LINK']['NAME'].'--,--edit14--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_edit14').'--,--IPROPERTY_TEMPLATES_ELEMENT_META_TITLE--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_IPROPERTY_TEMPLATES_ELEMENT_META_TITLE').'--,--IPROPERTY_TEMPLATES_ELEMENT_META_KEYWORDS--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_IPROPERTY_TEMPLATES_ELEMENT_META_KEYWORDS').'--,--IPROPERTY_TEMPLATES_ELEMENT_META_DESCRIPTION--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_IPROPERTY_TEMPLATES_ELEMENT_META_DESCRIPTION').'--,--IPROPERTY_TEMPLATES_ELEMENT_PAGE_TITLE--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_IPROPERTY_TEMPLATES_ELEMENT_PAGE_TITLE').'--,--IPROPERTY_TEMPLATES_ELEMENTS_PREVIEW_PICTURE--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_IPROPERTY_TEMPLATES_ELEMENTS_PREVIEW_PICTURE').'--,--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_ALT--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_ALT').'--,--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_TITLE--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_TITLE').'--,--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_NAME--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_NAME').'--,--IPROPERTY_TEMPLATES_ELEMENTS_DETAIL_PICTURE--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_IPROPERTY_TEMPLATES_ELEMENTS_DETAIL_PICTURE').'--,--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_ALT--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_ALT').'--,--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_TITLE--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_TITLE').'--,--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_NAME--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_NAME').'--,--SEO_ADDITIONAL--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_SEO_ADDITIONAL').'--,--TAGS--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_TAGS').'--;--edit2--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_edit2').'--,--SECTIONS--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_SECTIONS').'--;--edit10--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_edit10').'--,--CATALOG--#--'.GetMessage('WIZ_VIEW_NAME_OFFER_EL_CATALOG').'--;--',
    ),
    true);

?>
