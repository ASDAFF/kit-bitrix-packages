<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

use \Kit\Origami\Config\Option;

if (!CModule::IncludeModule("iblock"))
    return;

if(!defined("WIZARD_SITE_ID")) return;
if(!defined("WIZARD_SITE_DIR")) return;
if(!defined("WIZARD_SITE_PATH")) return;
if(!defined("WIZARD_TEMPLATE_ID")) return;
if(!defined("WIZARD_TEMPLATE_ABSOLUTE_PATH")) return;
if(!defined("WIZARD_THEME_ID")) return;

$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH . "/xml/" . LANGUAGE_ID . "/blog.xml";
$iblockCode = "kit_origami_blog";
$iblockType = "kit_origami_content";
$iblockXMLID = "kit_origami_blog";

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
    Option::Set('IBLOCK_TYPE_BLOG', "kit_origami_content", WIZARD_SITE_ID);
    Option::Set('IBLOCK_ID_BLOG', $iblockID, WIZARD_SITE_ID);
}

$_SESSION['KIT_ORIGAMI_WIZARD_CHANGE']["BLOG"] = $iblockID;

$arP = \Bitrix\Iblock\PropertyTable::getList(array('filter' => ['IBLOCK_ID' => $iblockID],'select' => ['ID', 'CODE', 'NAME']))->fetchAll();

$arP = array_column($arP, null, 'CODE');

CUserOptions::SetOption(
    'form',
    'form_element_'.$iblockID,
    array (
        'tabs' => 'edit1--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_edit1').'--,--ID--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_ID').'--,--DATE_CREATE--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_DATE_CREATE').'--,--TIMESTAMP_X--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_TIMESTAMP_X').'--,--ACTIVE--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_ACTIVE').'--,--ACTIVE_FROM--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_ACTIVE_FROM').'--,--ACTIVE_TO--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_ACTIVE_TO').'--,--NAME--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_NAME').'--,--CODE--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_CODE').'--,--SORT--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_SORT').'--;--edit5--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_edit5').'--,--PREVIEW_PICTURE--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_PREVIEW_PICTURE').'--,--PREVIEW_TEXT--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_PREVIEW_TEXT').'--;--edit6--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_edit6').'--,--DETAIL_PICTURE--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_DETAIL_PICTURE').'--,--IBLOCK_ELEMENT_PROP_VALUE--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_IBLOCK_ELEMENT_PROP_VALUE').'--,--PROPERTY_'.$arP['PHOTO']['ID'].'--#--'.$arP['PHOTO']['NAME'].'--,--DETAIL_TEXT--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_DETAIL_TEXT').'--;--cedit1--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_cedit1').'--,--PROPERTY_'.$arP['REGIONS']['ID'].'--#--'.$arP['REGIONS']['NAME'].'--,--PROPERTY_'.$arP['LINK_PRODUCTS']['ID'].'--#--'.$arP['LINK_PRODUCTS']['NAME'].'--;--edit14--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_edit14').'--,--IPROPERTY_TEMPLATES_ELEMENT_META_TITLE--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_IPROPERTY_TEMPLATES_ELEMENT_META_TITLE').'--,--IPROPERTY_TEMPLATES_ELEMENT_META_KEYWORDS--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_IPROPERTY_TEMPLATES_ELEMENT_META_KEYWORDS').'--,--IPROPERTY_TEMPLATES_ELEMENT_META_DESCRIPTION--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_IPROPERTY_TEMPLATES_ELEMENT_META_DESCRIPTION').'--,--IPROPERTY_TEMPLATES_ELEMENT_PAGE_TITLE--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_IPROPERTY_TEMPLATES_ELEMENT_PAGE_TITLE').'--,--IPROPERTY_TEMPLATES_ELEMENTS_PREVIEW_PICTURE--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_IPROPERTY_TEMPLATES_ELEMENTS_PREVIEW_PICTURE').'--,--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_ALT--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_ALT').'--,--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_TITLE--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_TITLE').'--,--IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_NAME--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_IPROPERTY_TEMPLATES_ELEMENT_PREVIEW_PICTURE_FILE_NAME').'--,--IPROPERTY_TEMPLATES_ELEMENTS_DETAIL_PICTURE--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_IPROPERTY_TEMPLATES_ELEMENTS_DETAIL_PICTURE').'--,--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_ALT--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_ALT').'--,--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_TITLE--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_TITLE').'--,--IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_NAME--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_IPROPERTY_TEMPLATES_ELEMENT_DETAIL_PICTURE_FILE_NAME').'--,--SEO_ADDITIONAL--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_SEO_ADDITIONAL').'--,--TAGS--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_TAGS').'--;--edit2--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_edit2').'--,--SECTIONS--#--'.GetMessage('WIZ_VIEW_NAME_BLOG_EL_SECTIONS').'--;--',
    ),
    true);
/*
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "/blog/index.php", array("BLOG_IBLOCK_ID" => $iblockID));
//CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "/blocks/blog_49/content.php", array("BLOG_IBLOCK_ID" => $iblockID));
//CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "/blocks/blog_3/content.php", array("BLOG_IBLOCK_ID" => $iblockID));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "/include/blocks/blog_square_left/content.php", array("BLOG_IBLOCK_ID" => $iblockID));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "/include/blocks/blog_square_right/content.php", array("BLOG_IBLOCK_ID" => $iblockID));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "/include/blocks/blog/content.php", array("BLOG_IBLOCK_ID" => $iblockID));
CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"] . "/include/blocks/blog_square_left/content.php", array("BLOG_IBLOCK_ID" => $iblockID));
CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"] . "/include/blocks/blog_square_right/content.php", array("BLOG_IBLOCK_ID" => $iblockID));
CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"] . "/include/blocks/blog/content.php", array("BLOG_IBLOCK_ID" => $iblockID));

*/
?>