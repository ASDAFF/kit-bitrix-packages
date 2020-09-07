<?
/** @global CMain $APPLICATION */
/** @global CDatabase $DB */

/** @global CUser $USER */

use Bitrix\Catalog;
use Bitrix\Currency;
use Bitrix\Iblock;
use Bitrix\Main;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Kit\Opengraph\OpengraphPageMetaTable;
use Kit\Opengraph\OpengraphCategoryTable;
use Kit\Opengraph\Helper\OpengraphMetaAction;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

if($APPLICATION->GetGroupRight('kit.opengraph') == "D")
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

if(isset($_REQUEST['sessid']) && $_REQUEST['sessid'] == bitrix_sessid()) {
    if($_REQUEST['action'])
        OpengraphMetaAction::groupAction($_REQUEST['ID'], $_REQUEST['action']['action_button_tbl_page_list']);
    else if($_REQUEST['type'] == 'E') {
        if(isset($_REQUEST['action_button_tbl_page_list']))
            OpengraphMetaAction::simpleAction($_REQUEST['ID'], $_REQUEST['action_button_tbl_page_list']);
    }else if($_REQUEST['type'] == 'S') {
    
    }else {
        OpengraphMetaAction::groupAction($_REQUEST['ID'], $_REQUEST['action_button_tbl_page_list']);
    }
}
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
Loader::includeModule("iblock");
IncludeModuleLangFile(__FILE__);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/iblock/prolog.php");

$arFilter = array(
//    "category" => $_REQUEST['CATEGORY_ID'],
    "CHECK_PERMISSIONS" => "Y",
    "MIN_PERMISSION" => "R",
);
$sTableID = "tbl_page_list";
$oSort = new CAdminSorting($sTableID, "timestamp_x", "desc");
$lAdmin = new CAdminUiList($sTableID, $oSort);
$lAdmin->bMultipart = true;

$sectionItems = array(
    "" => GetMessage("IBLOCK_ALL"),
    "0" => GetMessage("IBLOCK_UPPER_LEVEL"),
);

$rs = OpengraphCategoryTable::getList(array(
    'order' => array("ID"=>"asc"),
    'select' => array('ID', 'NAME'),
    'filter' => array('SITE_ID' => $_REQUEST['site'])
));

while($ar = $rs->fetch())
    $sectionItems[$ar["ID"]] = $ar["NAME"];

$arFilter = array();

if(empty($_POST) && isset($_REQUEST['CATEGORY_ID']) && isset($_REQUEST['apply_filter']))
    $arFilter['CATEGORY_ID'] = intval($_REQUEST['CATEGORY_ID']);

$arFilter['SITE_ID'] = $_REQUEST['site'];
//////////////////////////filter columns
$arFields = array_filter(array_keys(OpengraphPageMetaTable::getMap()), function($val) {
    return $val != 'OG_PROPS_ACTIVE' && $val != 'TW_PROPS_ACTIVE';
});

$langPrefix = 'KIT_OPENGRAPH_';

$filterFields = array(
    array(
        "id" => "ID",
        "name" => Loc::getMessage($langPrefix.'ID'),
        "type" => "number",
        "filterable" => "",
        "default" => true
    ),
    array(
        "id" => "CATEGORY_ID",
        "name" => Loc::getMessage($langPrefix.'CATEGORY_ID'),
        "type" => "list",
        "items" => $sectionItems,
        "filterable" => "",
    ),
    array(
        "id" => "NAME",
        "name" => Loc::getMessage($langPrefix.'NAME'),
        "filterable" => "",
        "quickSearch" => "",
        "default" => true
    ),
    array(
        "id" => "SORT",
        "name" => Loc::getMessage($langPrefix.'SORT'),
        "filterable" => "",
        "default" => true
    ),
    array(
        "id" => "ACTIVE_OG",
        "name" => Loc::getMessage($langPrefix.'ACTIVE_OG'),
        "type" => "list",
        "items" => array(
            "Y" => Loc::getMessage("IBLOCK_YES"),
            "N" => Loc::getMessage("IBLOCK_NO")
        ),
        "filterable" => "",
        "default" => true
    ),
    array(
        "id" => "OG_IMAGE_TYPE",
        "name" => Loc::getMessage($langPrefix.'OG_IMAGE_TYPE'),
        "filterable" => "",
    ),
    array(
        "id" => "OG_IMAGE_WIDTH",
        "name" => Loc::getMessage($langPrefix.'OG_IMAGE_WIDTH'),
        "filterable" => "",
    ),
    array(
        "id" => "OG_IMAGE_HEIGHT",
        "name" => Loc::getMessage($langPrefix.'OG_IMAGE_HEIGHT'),
        "filterable" => "",
    ),
    array(
        "id" => "OG_IMAGE_SECURE_URL",
        "name" => Loc::getMessage($langPrefix.'OG_IMAGE_SECURE_URL'),
        "filterable" => "",
    ),
    array(
        "id" => "OG_DESCRIPTION",
        "name" => Loc::getMessage($langPrefix.'OG_DESCRIPTION'),
        "filterable" => "",
    ),
    array(
        "id" => "OG_TITLE",
        "name" => Loc::getMessage($langPrefix.'OG_TITLE'),
        "filterable" => "",
        "default" => true
    ),
    array(
        "id" => "OG_URL",
        "name" => Loc::getMessage($langPrefix.'OG_URL'),
        "filterable" => "",
        "default" => true
    ),
    array(
        "id" => "OG_TYPE",
        "name" => Loc::getMessage($langPrefix.'OG_TYPE'),
        "filterable" => "",
        "default" => true
    ),
    array(
        "id" => "OG_IMAGE",
        "name" => Loc::getMessage($langPrefix.'OG_IMAGE'),
        "filterable" => "",
        "default" => true
    ),
    array(
        "id" => "ACTIVE_TW",
        "name" => Loc::getMessage($langPrefix.'ACTIVE_TW'),
        "type" => "list",
        "items" => array(
            "Y" => Loc::getMessage("IBLOCK_YES"),
            "N" => Loc::getMessage("IBLOCK_NO")
        ),
        "filterable" => "",
        "default" => true
    ),
    array(
        "id" => "TW_CARD",
        "name" => Loc::getMessage($langPrefix.'TW_CARD'),
        "filterable" => "",
        "default" => true
    ),
    array(
        "id" => "TW_TITLE",
        "name" => Loc::getMessage($langPrefix.'TW_TITLE'),
        "filterable" => "",
        "default" => true
    ),
    array(
        "id" => "TW_SITE",
        "name" => Loc::getMessage($langPrefix.'TW_SITE'),
        "filterable" => "",
    ),
    array(
        "id" => "TW_DESCRIPTION",
        "name" => Loc::getMessage($langPrefix.'TW_DESCRIPTION'),
        "filterable" => "",
    ),
    array(
        "id" => "TW_IMAGE_ALT",
        "name" => Loc::getMessage($langPrefix.'TW_IMAGE_ALT'),
        "filterable" => "",
    ),
    array(
        "id" => "TW_CREATOR",
        "name" => Loc::getMessage($langPrefix.'TW_CREATOR'),
        "filterable" => "",
    ),
    array(
        "id" => "TW_IMAGE",
        "name" => Loc::getMessage($langPrefix.'TW_IMAGE'),
        "filterable" => "",
    ),
    array(
        "id" => "TIMESTAMP_X",
        "name" => Loc::getMessage($langPrefix.'TIMESTAMP_X'),
        "type" => "date",
        "filterable" => ""
    ),
    array(
        "id" => "DATE_CREATE",
        "name" => Loc::getMessage($langPrefix.'DATE_CREATE'),
        "type" => "date",
        "filterable" => ""
    ),
    array(
        "id" => "SITE_ID",
        "name" => Loc::getMessage($langPrefix.'SITE_ID'),
        "filterable" => "",
    ),
);

$lAdmin->AddFilter(array_merge($filterFields, array(array(
    "id" => "FIND",
    "name" => 'find',
    "type" => "string",
    "quickSearch" => "",
))), $arFilter);

//unset($arFilter['FIND']);

$rsSite = Bitrix\Main\SiteTable::getList(array(
    'order' => array('SORT' => 'ASC'),
    'select' => array(
        'LID',
        'NAME'
    )
));
$arSites = array();
while($arSite = $rsSite->fetch()) {
    $arSites[$arSite['LID']] = "[$arSite[LID]] $arSite[NAME]";
}
// Handle edit action (check for permission before save!)
if($lAdmin->EditAction()) {
}
CJSCore::Init(array('date'));
// List header
$arHeader = array(
    array(
        "id" => "ID",
        "content" => Loc::getMessage($langPrefix.'ID'),
        "sort" => "ID",
        "align" => "center",
        "default" => true
    ),
    array(
        "id" => "NAME",
        "content" => Loc::getMessage($langPrefix.'NAME'),
        "sort" => "NAME",
        "default" => true
    ),
    array(
        "id" => "SORT",
        "content" => Loc::getMessage($langPrefix.'SORT'),
        "sort" => "SORT",
        "default" => true
    ),
    array(
        "id" => "ACTIVE_OG",
        "content" => Loc::getMessage($langPrefix.'ACTIVE_OG'),
        "sort" => "ACTIVE_OG",
        "default" => true
    ),
    array(
        "id" => "OG_IMAGE_TYPE",
        "content" => Loc::getMessage($langPrefix.'OG_IMAGE_TYPE'),
        "sort" => "OG_IMAGE_TYPE",
    ),
    array(
        "id" => "OG_IMAGE_WIDTH",
        "content" => Loc::getMessage($langPrefix.'OG_IMAGE_WIDTH'),
        "sort" => "OG_IMAGE_WIDTH",
    ),
    array(
        "id" => "OG_IMAGE_HEIGHT",
        "content" => Loc::getMessage($langPrefix.'OG_IMAGE_HEIGHT'),
        "sort" => "OG_IMAGE_HEIGHT",
    ),
    array(
        "id" => "OG_IMAGE_SECURE_URL",
        "content" => Loc::getMessage($langPrefix.'OG_IMAGE_SECURE_URL'),
        "sort" => "OG_IMAGE_SECURE_URL",
    ),
    array(
        "id" => "OG_DESCRIPTION",
        "content" => Loc::getMessage($langPrefix.'OG_DESCRIPTION'),
        "sort" => "OG_DESCRIPTION",
    ),
    array(
        "id" => "OG_TITLE",
        "content" => Loc::getMessage($langPrefix.'OG_TITLE'),
        "sort" => "OG_TITLE",
        'default' => true
    ),
    array(
        "id" => "OG_URL",
        "content" => Loc::getMessage($langPrefix.'OG_URL'),
        "sort" => "OG_URL",
        'default' => true
    ),
    array(
        "id" => "OG_TYPE",
        "content" => Loc::getMessage($langPrefix.'OG_TYPE'),
        "sort" => "OG_TYPE",
        'default' => true
    ),
    array(
        "id" => "OG_IMAGE",
        "content" => Loc::getMessage($langPrefix.'OG_IMAGE'),
    ),
    array(
        "id" => "ACTIVE_TW",
        "content" => Loc::getMessage($langPrefix.'ACTIVE_TW'),
        "sort" => "ACTIVE_TW",
        "default" => true
    ),
    array(
        "id" => "TW_CARD",
        "content" => Loc::getMessage($langPrefix.'TW_CARD'),
        "sort" => "TW_CARD",
        "default" => true
    ),
    array(
        "id" => "TW_TITLE",
        "content" => Loc::getMessage($langPrefix.'TW_TITLE'),
        "sort" => "TW_TITLE",
        "default" => true
    ),
    array(
        "id" => "TW_SITE",
        "content" => Loc::getMessage($langPrefix.'TW_SITE'),
        "sort" => "TW_SITE",
    ),
    array(
        "id" => "TW_IMAGE_ALT",
        "content" => Loc::getMessage($langPrefix.'TW_IMAGE_ALT'),
        "sort" => "TW_IMAGE_ALT",
    ),
    array(
        "id" => "TW_CREATOR",
        "content" => Loc::getMessage($langPrefix.'TW_CREATOR'),
        "sort" => "TW_CREATOR",
    ),
    array(
        "id" => "TW_IMAGE",
        "content" => Loc::getMessage($langPrefix.'TW_IMAGE'),
    ),
    array(
        "id" => "TIMESTAMP_X",
        "content" => Loc::getMessage($langPrefix.'TIMESTAMP_X'),
        "sort" => "TIMESTAMP_X",
    ),
    array(
        "id" => "DATE_CREATE",
        "content" => Loc::getMessage($langPrefix.'DATE_CREATE'),
        "sort" => "DATE_CREATE",
    ),
    array(
        "id" => "SITE_ID",
        "content" => Loc::getMessage($langPrefix.'SITE_ID'),
        "sort" => "SITE_ID",
    )
);

$lAdmin->AddHeaders($arHeader);
$lAdmin->AddVisibleHeaderColumn('ID');
$arSelectedFields = $lAdmin->GetVisibleHeaderColumns();

$sort = array("ID"=>"ASC");

if(isset($_REQUEST['by']) && isset($_REQUEST['order']))
    $sort = array($_REQUEST['by'] => $_REQUEST['order']);


$rsData = OpengraphPageMetaTable::GetMixedList($sort, $arFilter);
$rsData = new CAdminUiResult($rsData, $sTableID);
$rsData->NavStart();
$lAdmin->SetNavigationParams($rsData, array("BASE_LINK" => '/bitrix/admin/kit.opengraph_url_list.php'));
$boolIBlockElementAdd = CIBlockSectionRights::UserHasRightTo($IBLOCK_ID, $find_section_section, "section_element_bind");
$arRows = array();
$mainEntityEdit = false;

while($arRes = $rsData->NavNext(false)) {
    $arLinkParams = array(
        'lang' => LANGUAGE_ID,
        'site' => $_REQUEST['site']
    );
    
    $bReadOnly = true;
    
    $itemId = $arRes['ID'];
    $itemType = $arRes['TYPE'];
    if($itemType == "S") {
        $arLinkParams['CATEGORY_ID'] = $arRes['ID'];
        $arLinkParams['apply_filter'] = 'Y';
        
        $arLink = array();
        array_map(function($key, $value) use (&$arLink) {
            $arLink[] = $key."=".$value;
        }, array_keys($arLinkParams), $arLinkParams);
        
        $url_section = '/bitrix/admin/kit.opengraph_url_list.php?'.implode('&', $arLink);
    } else {
        $arLinkParams['ID'] = $arRes['ID'];
        if(isset($_REQUEST['CATEGORY_ID']))
            $arLinkParams['CATEGORY_ID'] = $_REQUEST['CATEGORY_ID'];
    
        $arLink = array();
        array_map(function($key, $value) use (&$arLink) {
            $arLink[] = $key."=".$value;
        }, array_keys($arLinkParams), $arLinkParams);
    
        $url_element = '/bitrix/admin/kit.opengraph_url_edit.php?'.implode('&', $arLink);
    }
    
    $bReadOnly = false;
    if(!$bReadOnly)
        $mainEntityEdit = true;
   
    $row = $lAdmin->AddRow($itemType.$itemId, $arRes, $url_element, GetMessage("IBLIST_A_LIST"));
    
    $arRows[$itemType.$itemId] = $row;
    
    if($itemType == "S")
        $row->AddViewField("NAME", '<a href="'.CHTTP::URN2URI($url_section).'" class="adm-list-table-icon-link" title="'.GetMessage("IBLIST_A_LIST").'"><span class="adm-submenu-item-link-icon adm-list-table-icon iblock-section-icon"></span><span class="adm-list-table-link">'.htmlspecialcharsbx($arRes['NAME']).'</span></a>');
    else
        $row->AddViewField("NAME", '<a href="'.$url_element.'" title="'.GetMessage("IBLIST_A_EDIT").'">'.htmlspecialcharsbx($arRes['NAME']).'</a>');

    if(in_array('NAME', $arSelectedFields))
        $row->AddInputField("NAME", Array('size' => '35'));
    
    if($itemType != 'S') {
        if(in_array('ACTIVE_OG', $arSelectedFields))
            $row->AddCheckField("ACTIVE_OG");
        if(in_array('ACTIVE_TW', $arSelectedFields))
            $row->AddCheckField("ACTIVE_TW");
        if(in_array('OG_IMAGE_TYPE', $arSelectedFields))
            $row->AddInputField("OG_IMAGE_TYPE");
        if(in_array('OG_IMAGE_WIDTH', $arSelectedFields))
            $row->AddInputField("OG_IMAGE_WIDTH");
        if(in_array('OG_IMAGE_HEIGHT', $arSelectedFields))
            $row->AddInputField("OG_IMAGE_HEIGHT");
        if(in_array('OG_IMAGE_SECURE_URL', $arSelectedFields))
            $row->AddInputField("OG_IMAGE_SECURE_URL");
        if(in_array('OG_DESCRIPTION', $arSelectedFields))
            $row->AddInputField("OG_DESCRIPTION");
        if(in_array('OG_TITLE', $arSelectedFields))
            $row->AddInputField("OG_TITLE");
        if(in_array('OG_URL', $arSelectedFields))
            $row->AddInputField("OG_URL");
        if(in_array('OG_TYPE', $arSelectedFields))
            $row->AddInputField("OG_TYPE");
        if(in_array('OG_IMAGE', $arSelectedFields))
            $row->AddViewField("OG_IMAGE", CFile::ShowImage($arRes['OG_IMAGE'], 50, 50, "", "", false));
        if(in_array('TW_CARD', $arSelectedFields))
            $row->AddInputField("TW_CARD");
        if(in_array('TW_TITLE', $arSelectedFields))
            $row->AddInputField("TW_TITLE");
        if(in_array('TW_SITE', $arSelectedFields))
            $row->AddInputField("TW_SITE");
        if(in_array('TW_DESCRIPTION', $arSelectedFields))
            $row->AddInputField("TW_DESCRIPTION");
        if(in_array('TW_IMAGE_ALT', $arSelectedFields))
            $row->AddInputField("TW_IMAGE_ALT");
        if(in_array('TW_CREATOR', $arSelectedFields))
            $row->AddInputField("TW_CREATOR");
        if(in_array('TW_IMAGE', $arSelectedFields))
            $row->AddViewField("TW_IMAGE", CFile::ShowImage($arRes['TW_IMAGE'], 50, 50, "", "", false));
    }
    
    if(in_array('SORT', $arSelectedFields))
        $row->AddInputField("SORT");
    if(in_array('TIMESTAMP_X', $arSelectedFields))
        $row->AddInputField("TIMESTAMP_X");
    if(in_array('DATE_CREATE', $arSelectedFields))
        $row->AddInputField("DATE_CREATE");
    if(in_array('SITE_ID', $arSelectedFields))
        $row->AddSelectField("SITE_ID", $arSites);
        
    if($itemType == "S")
        $row->AddViewField($itemType."ID", '<a href="/bitrix/admin/kit.opengraph_category_edit.php?lang=ru&site='.$_REQUEST['site'].'&ID='.$arRes['ID'].(isset($_REQUEST['CATEGORY_ID']) ? '&CATEGORY_ID='.$_REQUEST['category'] : '').'" title="'.GetMessage("IBLIST_A_EDIT").'">'.$itemId.'</a>');
    else
        $row->AddViewField("ID", '<a href="/bitrix/admin/kit.opengraph_url_edit.php?lang=ru&site='.$_REQUEST['site'].'&ID='.$arRes['ID'].(isset($_REQUEST['CATEGORY_ID']) ? '&CATEGORY_ID='.$_REQUEST['category'] : '').'" title="'.GetMessage("IBLIST_A_EDIT").'">'.$itemId.'</a>');
    
    if($APPLICATION->GetGroupRight('kit.opengraph') == "W") {
        $arActions = array();
        $arActions[] = array(
            "ICON" => "edit",
            "TEXT" => Loc::getMessage($langPrefix.'EDIT'),
            "ACTION" => $lAdmin->ActionRedirect('/bitrix/admin/kit.opengraph_'.(($itemType == 'S') ? 'category' : 'url').'_edit.php?lang=ru&site='.$_REQUEST['site'].'&ID='.$arRes['ID'].(isset($_REQUEST['CATEGORY_ID']) ? '&CATEGORY_ID='.$_REQUEST['CATEGORY_ID'] : '').''),
            "DEFAULT" => true,
        );
        $arActions[] = array(
            "ICON" => "delete",
            "TEXT" => Loc::getMessage($langPrefix.'DELETE'),
            "ACTION" => "if(confirm('".Loc::getMessage($langPrefix."CONFIRM_DEL_MESSAGE")."')) ".$lAdmin->ActionDoGroup($itemId, "delete", 'type='.$itemType),
        );
        if($itemType != "S") {
            if($arRes['ACTIVE_OG'] == 'Y')
                $arActions[] = array(
                    "ICON" => "deactive",
                    "TEXT" => Loc::getMessage($langPrefix.'DO_DEACTIVE_OG'),
                    "ACTION" => $lAdmin->ActionDoGroup($itemId, "deactive_og", 'type='.$itemType),
                );
            else
                $arActions[] = array(
                    "ICON" => "active",
                    "TEXT" => Loc::getMessage($langPrefix.'DO_ACTIVE_OG'),
                    "ACTION" => $lAdmin->ActionDoGroup($itemId, "active_og", 'type='.$itemType),
                );
            if($arRes['ACTIVE_TW'] == 'Y')
                $arActions[] = array(
                    "ICON" => "deactive",
                    "TEXT" => Loc::getMessage($langPrefix.'DO_DEACTIVE_TW'),
                    "ACTION" => $lAdmin->ActionDoGroup($itemId, "deactive_tw", 'type='.$itemType),
                );
            else
                $arActions[] = array(
                    "ICON" => "active",
                    "TEXT" => Loc::getMessage($langPrefix.'DO_ACTIVE_TW'),
                    "ACTION" => $lAdmin->ActionDoGroup($itemId, "active_tw", 'type='.$itemType),
                );
        }
        else {
            $arActions[] = array(
                "ICON" => "active",
                "TEXT" => "����������",
                "ACTION" => $lAdmin->ActionRedirect($url_section),
            );
        }
        $row->AddActions($arActions);
    }
}
// List footer
$lAdmin->AddFooter(array(
    array(
        "title" => GetMessage("MAIN_ADMIN_LIST_SELECTED"),
        "value" => $rsData->SelectedRowsCount()
    ),
    array(
        "counter" => true,
        "title" => GetMessage("MAIN_ADMIN_LIST_CHECKED"),
        "value" => "0"
    ),
));
// Action bar
$arActions = array();
if($mainEntityEdit) {
    $arActions = array(
        "edit" => Loc::getMessage($langPrefix.'EDIT'),
        "delete" => Loc::getMessage($langPrefix.'DELETE'),
        "for_all" => true,
        "active_og" => Loc::getMessage($langPrefix."DO_ACTIVE_OG"),
        "active_tw" => Loc::getMessage($langPrefix."DO_ACTIVE_TW"),
        "deactive_og" => Loc::getMessage($langPrefix."DO_DEACTIVE_OG"),
        "deactive_tw" => Loc::getMessage($langPrefix."DO_DEACTIVE_TW"),
    );
}
$chain = $lAdmin->CreateChain();
$chain->AddItem(array(
    "TEXT" => "NAME",
    "LINK" => htmlspecialcharsbx($sSectionUrl),
    "ONCLICK" => $lAdmin->ActionAjaxReload($sSectionUrl).';return false;',
));

if($APPLICATION->GetGroupRight('kit.opengraph') == "W")
    $lAdmin->AddGroupActionTable($arActions);

$sLastFolder = '';
$sSectionUrl = CIBlock::GetAdminSectionListLink(46, array('find_section_section' => 0));
$chain = $lAdmin->CreateChain();
if($find_section_section > 0) {
    $sLastFolder = $sSectionUrl;
    $nav = CIBlockSection::GetNavChain($IBLOCK_ID, $find_section_section, array(
        'ID',
        'NAME'
    ));
    while($ar_nav = $nav->GetNext()) {
        $sSectionUrl = CIBlock::GetAdminSectionListLink($IBLOCK_ID, array('find_section_section' => $ar_nav["ID"]));
        $chain->AddItem(array(
            "TEXT" => $ar_nav["NAME"],
            "LINK" => htmlspecialcharsbx($sSectionUrl),
            "ONCLICK" => $lAdmin->ActionAjaxReload($sSectionUrl).';return false;',
        ));
        if($ar_nav["ID"] != $find_section_section)
            $sLastFolder = $sSectionUrl;
    }
}

$option = \CUserOptions::getOption("main.ui.filter", 'tbl_page_list', array());

if(!is_array($option['filters']['tmp_filter']['fields']))
    $option['filters']['tmp_filter']['fields'] = array();

$arr = array_intersect_key($option['filters']['tmp_filter']['fields'], array_flip(array_column($filterFields, 'id')));
$arr = array_filter($arr, function($v, $k) {
    return !empty($v) || $k == 'CATEGORY_ID';
}, ARRAY_FILTER_USE_BOTH);

$arr = array_merge($arr, array(
    'lang' => LANGUAGE_ID,
    'site' => $_REQUEST['site']
));
$arrLink = array();
if(!empty($arr)) {
    array_map(function($key, $value) use (&$arrLink) {
        $arrLink[] = $key."=".$value;
    }, array_keys($arr), $arr);
}
//
$lAdmin->ShowChain($chain);

// toolbar
$boolBtnNew = false;
$aContext = array();
$aContext[] = array(
    "TEXT" => Loc::getMessage($langPrefix.'CREATE_PAGE'),
    "ICON" => ($boolBtnNew ? "" : "btn_new"),
    "LINK" => '/bitrix/admin/kit.opengraph_url_edit.php?'.implode('&', $arrLink)
);
$aContext[] = array(
    "TEXT" => Loc::getMessage($langPrefix.'CREATE_CATEGORY'),
    "ICON" => "btn_new",
    "LINK" => '/bitrix/admin/kit.opengraph_category_edit.php?'.implode('&', $arrLink)
);
//$lAdmin->setContextSettings(array("pagePath" => '/bitrix/admin/kit.opengraph_url_list.php'));
if($APPLICATION->GetGroupRight('kit.opengraph') == "W")
    $lAdmin->AddAdminContextMenu($aContext, false);

$lAdmin->CheckListMode();
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
//We need javascript not in excel mode
CJSCore::Init('file_input');
$lAdmin->DisplayFilter($filterFields);
$lAdmin->DisplayList(array("default_action" => $sec_list_url));
if(CIBlockRights::UserHasRightTo($IBLOCK_ID, $IBLOCK_ID, "iblock_edit") && !defined("CATALOG_PRODUCT")) {
    //echo BeginNote(), 'test node', ' <a href="'.htmlspecialcharsbx('/bitrix/admin/kit.opengraph_url_list.php?&lang='.LANGUAGE_ID.'&admin=Y&return_url='.urlencode("iblock_list_admin.php?".$sThisSectionUrl)).'">', 'test link', '</a>', EndNote();
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");