<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
    die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loader::IncludeModule('sotbit.origami');

use \Sotbit\Origami\Config\Option;

if(!CModule::IncludeModule("iblock") || !CModule::IncludeModule("catalog"))
    return;

if(COption::GetOptionString("sotbit.origami", "wizard_installed", "N", WIZARD_SITE_ID) == "Y" && !WIZARD_INSTALL_DEMO_DATA)
    return;

//catalog iblock import
$shopLocalization = $wizard->GetVar("shopLocalization");

$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/".LANGUAGE_ID."/services.xml";
/*$iblockXMLFilePrices = WIZARD_SERVICE_RELATIVE_PATH."/xml/".LANGUAGE_ID."/catalog_prices.xml";*/

$iblockCode = "sotbit_origami_services_"  . WIZARD_SITE_ID;
$iblockType = "sotbit_origami_catalog";
$iblockXMLID = 'sotbit_origami_services_'  . WIZARD_SITE_ID;

set_time_limit(0);

$rsIBlock = CIBlock::GetList(array(), array("XML_ID" => $iblockXMLID, "TYPE" => $iblockType));
$IBLOCK_CATALOG_ID = false;
if ($arIBlock = $rsIBlock->Fetch())
{
    $IBLOCK_CATALOG_ID = $arIBlock["ID"];
}

if (WIZARD_INSTALL_DEMO_DATA && $IBLOCK_CATALOG_ID)
{
    $boolFlag = true;
    $arSKU = CCatalogSKU::GetInfoByProductIBlock($IBLOCK_CATALOG_ID);
    if (!empty($arSKU))
    {
        $boolFlag = CCatalog::UnLinkSKUIBlock($IBLOCK_CATALOG_ID);
        if (!$boolFlag)
        {
            $strError = "";
            if ($ex = $APPLICATION->GetException())
            {
                $strError = $ex->GetString();
            }
            else
            {
                $strError = "Couldn't unlink iblocks";
            }
            //die($strError);
        }
        $boolFlag = true;/*
        $boolFlag = CIBlock::Delete($arSKU['IBLOCK_ID']);
        if (!$boolFlag)
        {
            $strError = "";
            if ($ex = $APPLICATION->GetException())
            {
                $strError = $ex->GetString();
            }
            else
            {
                $strError = "Couldn't delete offers iblock";
            }
            //die($strError);
        }*/
    }
    if ($boolFlag)
    {
        $oldSites2 = [];
        $rs = CIblock::GetSite($IBLOCK_CATALOG_ID);
        while($site = $rs->Fetch())
        {
            $oldSites2[] = $site['SITE_ID'];
        }
        $boolFlag = CIBlock::Delete($IBLOCK_CATALOG_ID);
        if (!$boolFlag)
        {
            $strError = "";
            if ($ex = $APPLICATION->GetException())
            {
                $strError = $ex->GetString();
            }
            else
            {
                $strError = "Couldn't delete catalog iblock";
            }
            //die($strError);
        }
    }
    if ($boolFlag)
    {
        $IBLOCK_CATALOG_ID = false;
    }
}

if($IBLOCK_CATALOG_ID == false)
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

    $IBLOCK_CATALOG_ID = WizardServices::ImportIBlockFromXML(
        $iblockXMLFile,
        $iblockCode,
        $iblockType,
        $sites,
        $permissions
    );

    \Bitrix\Catalog\Product\Sku::enableUpdateAvailable();
    if ($IBLOCK_CATALOG_ID < 1)
        return;

    $_SESSION["WIZARD_CATALOG_IBLOCK_ID"] = $IBLOCK_CATALOG_ID;


    $iblock = new CIBlock;
    $arFields = array(
        "FIELDS" => array(
            "PREVIEW_PICTURE" => array(
                "IS_REQUIRED" => "N",
                "DEFAULT_VALUE" => array(
                    "FROM_DETAIL" => "Y",
                    "SCALE" => "Y",
                    "WIDTH" => "235",
                    "HEIGHT" => "235",
                    "IGNORE_ERRORS" => "N",
                    "METHOD" => "resample",
                    "COMPRESSION" => 75,
                    "DELETE_WITH_DETAIL" => "Y",
                    "UPDATE_WITH_DETAIL" => "Y",
                ),
            ),
            "PREVIEW_TEXT_TYPE" => array(
                "IS_REQUIRED" => "Y",
                "DEFAULT_VALUE" => "text",
            ),
            "DETAIL_TEXT_TYPE" => array(
                "IS_REQUIRED" => "Y",
                "DEFAULT_VALUE" => "text",
            ),
            "CODE" => array(
                "IS_REQUIRED" => "N",
                "DEFAULT_VALUE" => array(
                    "UNIQUE" => "Y",
                    "TRANSLITERATION" => "Y",
                    "TRANS_LEN" => 100,
                    "TRANS_CASE" => "L",
                    "TRANS_SPACE" => "_",
                    "TRANS_OTHER" => "_",
                    "TRANS_EAT" => "Y",
                    "USE_GOOGLE" => "N",
                ),
            ),
            "SECTION_CODE" => array(
                "IS_REQUIRED" => "Y",
                "DEFAULT_VALUE" => array(
                    "UNIQUE" => "Y",
                    "TRANSLITERATION" => "Y",
                    "TRANS_LEN" => 100,
                    "TRANS_CASE" => "L",
                    "TRANS_SPACE" => "_",
                    "TRANS_OTHER" => "_",
                    "TRANS_EAT" => "Y",
                    "USE_GOOGLE" => "N",
                ),
            ),
        ),
        "CODE" => $iblockCode,
        "XML_ID" => $iblockXMLID,
    );

    $iblock->Update($IBLOCK_CATALOG_ID, $arFields);
}
else
{
    $arSites = array();
    $db_res = CIBlock::GetSite($IBLOCK_CATALOG_ID);
    while ($res = $db_res->Fetch())
        $arSites[] = $res["LID"];
    if (!in_array(WIZARD_SITE_ID, $arSites))
    {
        $arSites[] = WIZARD_SITE_ID;
        $iblock = new CIBlock;
        $iblock->Update($IBLOCK_CATALOG_ID, array("LID" => $arSites));
    }
}

if($IBLOCK_CATALOG_ID) {
    Option::Set('IBLOCK_TYPE_SERVICES', "sotbit_origami_catalog", WIZARD_SITE_ID);
    Option::Set('IBLOCK_ID_SERVICES', $IBLOCK_CATALOG_ID, WIZARD_SITE_ID);
}

$obUserField = new CUserTypeEntity();
$arUserField = array(
    "ENTITY_ID" => 'IBLOCK_'.$IBLOCK_CATALOG_ID.'_SECTION',
    "FIELD_NAME" => 'UF_DETAIL_DESCRIPT',
    "USER_TYPE_ID" => 'html',
    "XML_ID" => 'UF_DETAIL_DESCRIPT',
    "SORT" => 100,
    "MULTIPLE" => "N",
    "MANDATORY" => 'N',
    "SHOW_FILTER" => "N",
    "SHOW_IN_LIST" => "Y",
    "EDIT_IN_LIST" => "Y",
    "IS_SEARCHABLE" => "N",
    "SETTINGS" => array(),
);

$userFieldID = $obUserField->Add($arUserField);
if($userFieldID > 0)
    $obUserField->Update(
        $userFieldID,
        [
            'EDIT_FORM_LABEL' => ['ru' => Loc::getMessage('WZD_USERFIELD_UF_DESCR_BOTTOM')],
            'LIST_COLUMN_LABEL' => ['ru' => Loc::getMessage('WZD_USERFIELD_UF_DESCR_BOTTOM')],
            'LIST_FILTER_LABEL' => ['ru' => Loc::getMessage('WZD_USERFIELD_UF_DESCR_BOTTOM')],
            'ERROR_MESSAGE' => ['ru' => Loc::getMessage('WZD_USERFIELD_UF_DESCR_BOTTOM')],
            'HELP_MESSAGE' => ['ru' => Loc::getMessage('WZD_USERFIELD_UF_DESCR_BOTTOM')],
        ]
    );
?>
