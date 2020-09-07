<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

if (!CModule::IncludeModule("kit.origami"))
    return;

use \Kit\Origami\Config\Option;
use Bitrix\Sender\MailingTable;

if (!CModule::IncludeModule("iblock"))
    return;

if(!defined("WIZARD_SITE_ID")) return;
if(!defined("WIZARD_SITE_DIR")) return;
if(!defined("WIZARD_SITE_PATH")) return;
if(!defined("WIZARD_TEMPLATE_ID")) return;
if(!defined("WIZARD_TEMPLATE_ABSOLUTE_PATH")) return;
if(!defined("WIZARD_THEME_ID")) return;

$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH . "/xml/" . LANGUAGE_ID . "/vlog.xml";
$iblockCode = "kit_origami_vlog_"  . WIZARD_SITE_ID;
$iblockType = "kit_origami_content";
$iblockXMLID = "kit_origami_vlog_"  . WIZARD_SITE_ID;

$rsIBlock = CIBlock::GetList(array(), array("XML_ID" => $iblockXMLID, "TYPE" => $iblockType));
$iblockID = false;
if ($arIBlock = $rsIBlock->Fetch()) {
    $iblockID = $arIBlock["ID"];
    if (WIZARD_INSTALL_DEMO_DATA) {
        $oldSites2 = [];
        $rs = CIblock::GetSite($arIBlock["ID"]);
        while($site = $rs->Fetch())
        {
            $oldSites2[] = $site['SITE_ID'];
        }
        CIBlock::Delete($arIBlock["ID"]);
        $iblockID = false;
    }
}

if ($iblockID == false) {
    $permissions = Array(
        "1" => "X",
        "2" => "R"
    );
    $dbGroup = CGroup::GetList($by = "", $order = "", Array("STRING_ID" => "content_editor"));
    if ($arGroup = $dbGroup->Fetch()) {
        $permissions[$arGroup["ID"]] = 'W';
    };

    $sites = [WIZARD_SITE_ID];

    if($oldSites2 && is_array($oldSites2))
    {
        $sites = array_merge($sites,$oldSites2);
    }

    $iblockID = WizardServices::ImportIBlockFromXML(
        $iblockXMLFile,
        $iblockXMLID,
        $iblockType,
        $sites,
        $permissions
    );

    if ($iblockID < 1)
        return;

    //IBlock fields
    $iblock = new CIBlock;
    $arFields = Array(
        "ACTIVE" => "Y",
        "FIELDS" => array(
            'IBLOCK_SECTION' => array(
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',),
            'ACTIVE' => array(
                'IS_REQUIRED' => 'Y',
                'DEFAULT_VALUE' => 'Y',),
            'ACTIVE_FROM' => array('IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '=today',),
            'ACTIVE_TO' => array(
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',),
            'SORT' => array(
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',),
            'NAME' => array(
                'IS_REQUIRED' => 'Y',
                'DEFAULT_VALUE' => '',),
            'PREVIEW_PICTURE' => array(
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => array(
                    'FROM_DETAIL' => 'N',
                    'SCALE' => 'N',
                    'WIDTH' => '',
                    'HEIGHT' => '',
                    'IGNORE_ERRORS' => 'N',
                    'METHOD' => 'resample',
                    'COMPRESSION' => 95,
                    'DELETE_WITH_DETAIL' => 'N',
                    'UPDATE_WITH_DETAIL' => 'N',),),
            'PREVIEW_TEXT_TYPE' => array(
                'IS_REQUIRED' => 'Y',
                'DEFAULT_VALUE' => 'text',),
            'PREVIEW_TEXT' => array(
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',),
            'DETAIL_PICTURE' => array(
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => array(
                    'SCALE' => 'N',
                    'WIDTH' => '',
                    'HEIGHT' => '',
                    'IGNORE_ERRORS' => 'N',
                    'METHOD' => 'resample',
                    'COMPRESSION' => 95,),),
            'DETAIL_TEXT_TYPE' => array(
                'IS_REQUIRED' => 'Y',
                'DEFAULT_VALUE' => 'text',),
            'DETAIL_TEXT' => array(
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',),
            'XML_ID' => array(
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',),
            'CODE' => array(
                'IS_REQUIRED' => 'Y',
                'DEFAULT_VALUE' => array(
                    'UNIQUE' => 'Y',
                    'TRANSLITERATION' => 'Y',
                    'TRANS_LEN' => 100,
                    'TRANS_CASE' => 'L',
                    'TRANS_SPACE' => '_',
                    'TRANS_OTHER' => '_',
                    'TRANS_EAT' => 'Y',
                    'USE_GOOGLE' => 'Y',),),
            'TAGS' => array(
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',),
            'SECTION_NAME' => array(
                'IS_REQUIRED' => 'Y',
                'DEFAULT_VALUE' => '',),
            'SECTION_PICTURE' => array(
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => array(
                    'FROM_DETAIL' => 'N',
                    'SCALE' => 'N',
                    'WIDTH' => '',
                    'HEIGHT' => '',
                    'IGNORE_ERRORS' => 'N',
                    'METHOD' => 'resample',
                    'COMPRESSION' => 95,
                    'DELETE_WITH_DETAIL' => 'N',
                    'UPDATE_WITH_DETAIL' => 'N',),),
            'SECTION_DESCRIPTION_TYPE' => array(
                'IS_REQUIRED' => 'Y',
                'DEFAULT_VALUE' => 'text',),
            'SECTION_DESCRIPTION' => array(
                'IS_REQUIRED' => 'N', 'DEFAULT_VALUE' => '',),
            'SECTION_DETAIL_PICTURE' => array(
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => array(
                    'SCALE' => 'N',
                    'WIDTH' => '',
                    'HEIGHT' => '',
                    'IGNORE_ERRORS' => 'N',
                    'METHOD' => 'resample',
                    'COMPRESSION' => 95,),),
            'SECTION_XML_ID' => array(
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => '',),
            'SECTION_CODE' => array(
                'IS_REQUIRED' => 'N',
                'DEFAULT_VALUE' => array(
                    'UNIQUE' => 'N',
                    'TRANSLITERATION' => 'N',
                    'TRANS_LEN' => 100,
                    'TRANS_CASE' => 'L',
                    'TRANS_SPACE' => '_',
                    'TRANS_OTHER' => '_',
                    'TRANS_EAT' => 'Y',
                    'USE_GOOGLE' => 'N',),),),
        "CODE" => $iblockCode,
        "XML_ID" => $iblockXMLID,
        //"NAME" => "[".WIZARD_SITE_ID."] ".$iblock->GetArrayByID($iblockID, "NAME")
    );

    $iblock->Update($iblockID, $arFields);

} else {
    $arSites = array();
    $db_res = CIBlock::GetSite($iblockID);
    while ($res = $db_res->Fetch())
        $arSites[] = $res["LID"];
    if (!in_array(WIZARD_SITE_ID, $arSites)) {
        $arSites[] = WIZARD_SITE_ID;
        $iblock = new CIBlock;
        $iblock->Update($iblockID, array("LID" => $arSites));
    }
}
$dbSite = CSite::GetByID(WIZARD_SITE_ID);
if ($arSite = $dbSite->Fetch())
    $lang = $arSite["LANGUAGE_ID"];
if (strlen($lang) <= 0)
    $lang = "ru";


if($iblockID) {
    Option::Set('IBLOCK_TYPE_VLOG', "kit_origami_content", WIZARD_SITE_ID);
    Option::Set('IBLOCK_ID_VLOG', $iblockID, WIZARD_SITE_ID);
}

//$arFieldsVlogIb = array(
//    "NAME" => GetMessage('WIZ_VIEW_NAME_VLOG_ID_VIDEO'),
//    "ACTIVE" => "Y",
//    "SORT" => "600",
//    "CODE" => "VIDEO_LINK",
//    "PROPERTY_TYPE" => "S",
//    //"USER_TYPE" => "HTML",
//    "IBLOCK_ID" => $iblockID,
//);

//$ibp = new CIBlockProperty;
//$ibp->Add($arFieldsVlogIb);

$id = MailingTable::add(
    array(
        "NAME" => GetMessage('WIZ_VIEW_NAME_TITLE_CAMPAIGN'),
        "ACTIVE" => "Y",
        "SITE_ID" => $SITE_ID,
        "IS_PUBLIC" => "Y",
        "TRACK_CLICK" => "N",
        "IS_TRIGGER" => "N"
    )
);

$_SESSION['KIT_ORIGAMI_WIZARD_CHANGE']["VLOG"] = $iblockID;

/*
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/promotions/index.php", array("SALES_IBLOCK_ID" => $iblockID));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/include/blocks/promotions/content.php", Array("SALES_IBLOCK_ID" => $iblockID));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/include/blocks/promotions_horizontal/content.php", Array("SALES_IBLOCK_ID" => $iblockID));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/include/blocks/promotions_vertical/content.php", Array("SALES_IBLOCK_ID" => $iblockID));
CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"]."/include/blocks/promotions/content.php", Array("SALES_IBLOCK_ID" => $iblockID));
CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"]."/include/blocks/promotions_horizontal/content.php", Array("SALES_IBLOCK_ID" => $iblockID));
CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"]."/include/blocks/promotions_vertical/content.php", Array("SALES_IBLOCK_ID" => $iblockID));
*/
?>
