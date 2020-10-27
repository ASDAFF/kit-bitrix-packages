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
use \Kit\Crosssell\Orm\CrosssellTable;
use \Kit\Crosssell\Orm\CrosssellCategoryTable;
use \Kit\Crosssell\Helper\crosssellaction;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
if(!Loader::includeModule('kit.crosssell')) {
    die();
}
$APPLICATION->SetTitle(Loc::GetMessage("CROSSELL_LIST_PAGE_TITLE"));
if($APPLICATION->GetGroupRight('kit.crosssell') == "D")
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

//counting crosssell //--------------------------------------------------------------------------------------------------------------------------------------------------
$count = CrosssellTable::getCount(array("TYPE_BLOCK" => 'CROSSSELL'));
$toShowGenAllBtn = true;
if($count < 1) {
    $toShowGenAllBtn = false;
}

//action handlers //--------------------------------------------------------------------------------------------------------------------------------------------------
if(isset($_REQUEST['sessid']) && $_REQUEST['sessid'] == bitrix_sessid())
{
    if(isset($_REQUEST['action_button_kit_crosssell_options']))
    {
        if($_REQUEST['action_button_kit_crosssell_options'] == 'delete')
        {
            if(is_array($_POST['ID'])) {
                CrosssellAction::groupAction($_POST['ID'], $_REQUEST['action_button_kit_crosssell_options']);
            } else {
                // burger Delete
                CrosssellAction::simpleAction($_REQUEST['ID'], $_REQUEST['action_button_kit_crosssell_options'], $_REQUEST['type']);
            }
        }
        if($_REQUEST['action_button_kit_crosssell_options'] == 'edit') {
            CrosssellAction::groupAction($_POST['FIELDS'], $_REQUEST['action_button_kit_crosssell_options']);
        }
    }

    if(isset($_REQUEST['delete']))
    {

        CrosssellAction::simpleAction($_REQUEST['delete'], 'delete', $_REQUEST['type']);
        LocalRedirect('/bitrix/admin/kit_crosssell_list.php?lang='.SITE_ID.(isset($_REQUEST['site']) ? '&site='.$_REQUEST['site'] : ''));
    }

}

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
CJSCore::Init(array("jquery"));

?>
    <link rel="stylesheet" href="/bitrix/css/kit.crosssell/style.css">
<?
Loader::includeModule("iblock");
IncludeModuleLangFile(__FILE__);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/iblock/prolog.php");

//BX //--------------------------------------------------------------------------------------------------------------------------------------------------
$arFilter = array(
    "CHECK_PERMISSIONS" => "Y",
    "MIN_PERMISSION" => "R",
);
$sTableID = "kit_crosssell_options";
$oSort = new CAdminSorting($sTableID, "timestamp_x", "desc");
$lAdmin = new CAdminUiList($sTableID, $oSort);
$lAdmin->bMultipart = true;
$sectionItems = array(
    "" => GetMessage("IBLOCK_ALL"),
    "0" => GetMessage("IBLOCK_UPPER_LEVEL"),
);

// set your category table //--------------------------------------------------------------------------------------------------------------------------------------------------
$rs = CrosssellCategoryTable::getList(array(
    'order' => array("ID"=>"asc"),
    'select' => array('ID', 'NAME'),
    'filter' => array('SITE_ID' => $_REQUEST['site'])
));
while($ar = $rs->fetch())
    $sectionItems[$ar["ID"]] = $ar["NAME"];
$arFilter = array();

if(empty($_POST) && isset($_REQUEST['CATEGORY_ID'])/* && isset($_REQUEST['apply_filter'])*/)
    $arFilter['CATEGORY_ID'] = intval($_REQUEST['CATEGORY_ID']);

$arFilter['SITE_ID'] = $_REQUEST['site'];


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

if (isset($_REQUEST['CATEGORY_ID']) && ($_REQUEST['CATEGORY_ID'] != ''))
{
    $aMenu= array(
        array(
            "TEXT" => GetMessage( "KIT_CROSSSELL_EDIT_LIST" ),
            "TITLE" => GetMessage( "KIT_CROSSSELL_EDIT_LIST_TITLE" ),
            "LINK" => '/bitrix/admin/kit_crosssell_list.php?lang='.SITE_ID.(isset($_REQUEST['site']) ? '&site='.$_REQUEST['site'] : ''),
            "ICON" => "btn_list"
        )
    );

    $context = new CAdminContextMenu( $aMenu );
    $context->Show();
}

//filter+search //--------------------------------------------------------------------------------------------------------------------------------------------------
$langPrefix = 'KIT_CROSSSELL_';
$filterFields = array(
    array(
        "id" => "ID",
        "name" => Loc::getMessage($langPrefix.'ID'),
        "type" => "number",
        "filterable" => "",
        "default" => true
    ),
    array(
        "id" => "SITES",
        "name" => Loc::getMessage($langPrefix.'SITES'),
        "type" => 'list',
        "items" => $arSites,
        "filterable" => "",
        "quickSearch" => "",
        "default" => true
    ),
    array(
        "id" => "NAME",
        "name" => Loc::getMessage($langPrefix.'NAME'),
        "filterable" => "",
        "quickSearch" => "",
        "default" => true
    ),
    array(
        "id" => "TYPE_BLOCK",
        "name" => Loc::getMessage($langPrefix.'TYPE'),
        "type" => 'list',
        "items" => array('COLLECTION' => Loc::getMessage($langPrefix.'COLLECTION'), 'CROSSSELL' => Loc::getMessage($langPrefix.'CROSSSELL')),
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
        "id" => "NUMBER_PRODUCTS",
        "name" => Loc::getMessage($langPrefix.'NUMBER_PRODUCTS'),
        "filterable" => "",
        "default" => true
    ),
    array(
        "id" => "Active",
        "name" => Loc::getMessage($langPrefix.'ACTIVE'),
        "filterable" => "",
        "default" => true
    ),
);

// //--------------------------------------------------------------------------------------------------------------------------------------------------
$lAdmin->AddFilter(array_merge($filterFields, array(array(
    "id" => "FIND",
    "name" => 'find',
    "type" => "string",
    "quickSearch" => "",
))), $arFilter);

//Handle edit action (check for permission before save!) //--------------------------------------------------------------------------------------------------------------------------------------------------
if($lAdmin->EditAction()) {
    //TODO
}
CJSCore::Init(array('date'));

//headerItems //--------------------------------------------------------------------------------------------------------------------------------------------------
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
        "id" => "TYPE_BLOCK",
        "content" => Loc::getMessage($langPrefix.'TYPE'),
        "sort" => "TYPE_BLOCK",
        "default" => true
    ),
    array(
        "id" => "SITES",
        "content" => Loc::getMessage($langPrefix.'SITES'),
        "sort" => "SITES",
        "default" => true
    ),
    array(
        "id" => "Active",
        "content" => Loc::getMessage($langPrefix.'ACTIVE'),
        "sort" => "Active",
        "default" => true
    ),
    array(
        "id" => "NUMBER_OF_MATCHED_PRODUCTS",
        "content" => Loc::getMessage($langPrefix.'NUMBER_OF_MATCHED'),
        "sort" => "NUMBER_OF_MATCHED_PRODUCTS",
        "default" => true
    ),
    array(
        "id" => "SORT",
        "content" => Loc::getMessage($langPrefix.'SORT'),
        "sort" => "SORT",
        "default" => true
    ),
    array(
        "id" => "SYMBOL_CODE",
        "content" => Loc::getMessage($langPrefix.'SYMBOL_CODE'),
        "sort" => "SYMBOL_CODE",
        "default" => true
    ),
    array(
        "id" => "FOREIGN_CODE",
        "content" => Loc::getMessage($langPrefix.'FOREIGN_CODE'),
        "sort" => "FOREIGN_CODE",
    ),
    array(
        "id" => "SORT_ORDER",
        "content" => Loc::getMessage($langPrefix.'SORT_ORDER'),
        "sort" => "SORT_ORDER",
    ),
    array(
        "id" => "SORT_BY",
        "content" => Loc::getMessage($langPrefix.'SORT_BY'),
        "sort" => "SORT_BY",
    ),
    array(
        "id" => "NUMBER_PRODUCTS",
        "content" => Loc::getMessage($langPrefix.'NUMBER_PRODUCTS'),
        "sort" => "NUMBER_PRODUCTS",
    ),
);

$arActive = [
    'Y' => Loc::getMessage($langPrefix.'YES'),
    'N' => Loc::getMessage($langPrefix.'NO')
];

$arType = [
    "COLLECTION" =>  Loc::getMessage($langPrefix.'COLLECTION'),
    "CROSSSELL" => Loc::getMessage($langPrefix.'CROSSSELL')
];

$lAdmin->AddHeaders($arHeader);
$lAdmin->AddVisibleHeaderColumn('ID');
$arSelectedFields = $lAdmin->GetVisibleHeaderColumns();
$sort = array("ID"=>"ASC");
if(isset($_REQUEST['by']) && isset($_REQUEST['order']))
    $sort = array($_REQUEST['by'] => $_REQUEST['order']);

// Set item table //--------------------------------------------------------------------------------------------------------------------------------------------------
$rsData = CrosssellTable::GetMixedList($sort, $arFilter);
$rsData = new CAdminUiResult($rsData, $sTableID);
$rsData->NavStart();
$lAdmin->SetNavigationParams($rsData, array("BASE_LINK" => '/bitrix/admin/kit_crosssell_list.php'));
$boolIBlockElementAdd = CIBlockSectionRights::UserHasRightTo($IBLOCK_ID, $find_section_section, "section_element_bind");
$arRows = array();
$mainEntityEdit = false;

while($arRes = $rsData->NavNext(false))
{
    $element = CrosssellTable::getById($arRes['ID']);
    $elementRes = $element->fetch();
    $arLinkParams = array(
        'lang' => LANGUAGE_ID,
        'site' => $_REQUEST['site'],
        'TYPE' => $elementRes['TYPE_BLOCK']
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

        $url_section = '/bitrix/admin/kit_crosssell_list.php?'.implode('&', $arLink);
    } else {
        $arLinkParams['ID'] = $arRes['ID'];
        if(isset($_REQUEST['CATEGORY_ID']))
            $arLinkParams['CATEGORY_ID'] = $_REQUEST['CATEGORY_ID'];

        $arLink = array();
        array_map(function($key, $value) use (&$arLink) {
            $arLink[] = $key."=".$value;
        }, array_keys($arLinkParams), $arLinkParams);

        $url_element = '/bitrix/admin/kit_crosssell.php?'.implode('&', $arLink);
    }

    $bReadOnly = false;
    if(!$bReadOnly)
        $mainEntityEdit = true;

    // TYPE ACTIVE // ------------------------------------------------------------------------------------------------------------------------------------------------------------
    $arRes['TYPE_BLOCK'] = $arType[$arRes['TYPE_BLOCK']];
    $arRes['Active'] = $arActive[$arRes['Active']];

    // Sites // ------------------------------------------------------------------------------------------------------------------------------------------------------------
    $arSitesTmp = unserialize($arRes['SITES']);
    if ($arSitesTmp) {
        $arRes['SITES'] = implode(', ', $arSitesTmp);
    }

    $row = $lAdmin->AddRow($itemType.$itemId, $arRes, $url_element, GetMessage("IBLIST_A_LIST"));

    $arRows[$itemType.$itemId] = $row;

    //category or item //--------------------------------------------------------------------------------------------------------------------------------------------------
    if($itemType == "S")
        $row->AddViewField("NAME", '<a href="'.CHTTP::URN2URI($url_section).'" class="adm-list-table-icon-link" title="'.GetMessage("IBLIST_A_LIST").'"><span class="adm-submenu-item-link-icon adm-list-table-icon iblock-section-icon"></span><span class="adm-list-table-link">'.htmlspecialcharsbx($arRes['NAME']).'</span></a>');
    else
        $row->AddViewField("NAME", '<a href="'.$url_element.'" title="'.GetMessage("IBLIST_A_EDIT").'">'.htmlspecialcharsbx($arRes['NAME']).'</a>');

    //EDIT FIELDS//--------------------------------------------------------------------------------------------------------------------------------------------------
    if(in_array('NAME', $arSelectedFields))
        $row->AddInputField("NAME", Array('size' => '35'));
    if(in_array('SORT', $arSelectedFields))
        $row->AddInputField("SORT");
    if(in_array('Active', $arSelectedFields))
        $row->AddInputField("Active");
    if(in_array('SORT_BY', $arSelectedFields))
        $row->AddInputField("SORT_BY");
    if(in_array('NUMBER_PRODUCTS', $arSelectedFields))
        $row->AddInputField("NUMBER_PRODUCTS");
    if(in_array('SORT_ORDER', $arSelectedFields))
        $row->AddInputField("SORT_ORDER");
    if(in_array('SYMBOL_CODE', $arSelectedFields))
        $row->AddInputField("SYMBOL_CODE");
    if(in_array('FOREIGN_CODE', $arSelectedFields))
        $row->AddInputField("FOREIGN_CODE");

    if($itemType == "S")
        $row->AddViewField($itemType."ID", '<a href="/bitrix/admin/kit_crosssell_category.php?lang=ru&site='.$_REQUEST['site'].'&ID='.$arRes['ID'].(isset($_REQUEST['CATEGORY_ID']) ? '&CATEGORY_ID='.$_REQUEST['category'] : '').'" title="'.GetMessage("IBLIST_A_EDIT").'">'.$itemId.'</a>');
    else
        $row->AddViewField("ID", '<a href="/bitrix/admin/kit_crosssell.php?lang=ru&site='.$_REQUEST['site'].'&ID='.$arRes['ID'].(isset($_REQUEST['CATEGORY_ID']) ? '&CATEGORY_ID='.$_REQUEST['category'] : '').
            ( $arRes["TYPE_BLOCK"] == Loc::GetMessage("COLLECTION_PRODUCTS_COLLECTIONS") ? "&TYPE=COLLECTION" : "&TYPE=CROSSSELL" ).'" title="'.GetMessage("IBLIST_A_EDIT").'">'.$itemId.'</a>');

    //Burger Menu//--------------------------------------------------------------------------------------------------------------------------------------------------
    if($APPLICATION->GetGroupRight('kit.crosssell') == "W") {
        $arActions = array();
        $arActions[] = array(
            "ICON" => "edit",
            "TEXT" => Loc::getMessage($langPrefix.'EDIT'),
            "ACTION" => $lAdmin->ActionRedirect('/bitrix/admin/kit_crosssell'.(($itemType == 'S') ? '_category' : '').'.php?lang=ru&site='.$_REQUEST['site'] .
                ( $arRes["TYPE_BLOCK"] == Loc::GetMessage("COLLECTION_PRODUCTS_COLLECTIONS") ? "&TYPE=COLLECTION" : "&TYPE=CROSSSELL" ) .
                '&ID='.$arRes['ID'].(isset($_REQUEST['CATEGORY_ID']) ? '&CATEGORY_ID='.$_REQUEST['CATEGORY_ID'] : '').''),
            "DEFAULT" => true,
        );
        $arActions[] = array(
            "ICON" => "delete",
            "TEXT" => Loc::getMessage($langPrefix.'DELETE'),
            "ACTION" => "if(confirm('".Loc::getMessage($langPrefix."CONFIRM_DEL_MESSAGE")."')) ".$lAdmin->ActionDoGroup($itemId, "delete", 'type='.$itemType),
        );
        $row->AddActions($arActions);
    }
}

//List footer//--------------------------------------------------------------------------------------------------------------------------------------------------
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

//action bottom bar//--------------------------------------------------------------------------------------------------------------------------------------------------
$arActions = array();
if($mainEntityEdit) {
    $arActions = array(
        "edit" => Loc::getMessage($langPrefix.'EDIT'),
        "delete" => Loc::getMessage($langPrefix.'DELETE'),
        "for_all" => true,
    );
}
$chain = $lAdmin->CreateChain();
$chain->AddItem(array(
    "TEXT" => "NAME",
    "LINK" => htmlspecialcharsbx($sSectionUrl),
    "ONCLICK" => $lAdmin->ActionAjaxReload($sSectionUrl).';return false;',
));

if($APPLICATION->GetGroupRight('kit.crosssell') == "W")
    $lAdmin->AddGroupActionTable($arActions);

//filter BX//--------------------------------------------------------------------------------------------------------------------------------------------------
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
    'site' => $_REQUEST['site'],
    'CATEGORY_ID' => $_REQUEST['CATEGORY_ID']
));
$arrLink = array();
if(!empty($arr)) {
    array_map(function($key, $value) use (&$arrLink) {
        $arrLink[] = $key."=".$value;
    }, array_keys($arr), $arr);
}

//
$lAdmin->ShowChain($chain);

//toolbar create/category//--------------------------------------------------------------------------------------------------------------------------------------------------
$boolBtnNew = true;
$aContext = array();

// set category id into createpage //
$aContext[] = array(
    "TEXT" => Loc::getMessage($langPrefix.'CREATE_CONDITION'),
    "ICON" => ($boolBtnNew ? "" : "btn_new"),
    "LINK" => '/bitrix/admin/kit_crosssell.php?' .implode('&', $arrLink) . '&TYPE=CROSSSELL'
);
$aContext[] = array(
    "TEXT" => Loc::getMessage($langPrefix.'CREATE_CONDITION_CROSSSELL'),
    "ICON" => ($boolBtnNew ? "" : "btn_new"),
    "LINK" => '/bitrix/admin/kit_crosssell.php?' .implode('&', $arrLink) . '&TYPE=CROSSSELL'
);
$aContext[] = array(
    "TEXT" => Loc::getMessage($langPrefix.'CREATE_CONDITION_COLLECTION'),
    "ICON" => ($boolBtnNew ? "" : "btn_new"),
    "LINK" => '/bitrix/admin/kit_crosssell.php?' .implode('&', $arrLink) . '&TYPE=COLLECTION'
);
$aContext[] = array(
    "TEXT" => Loc::getMessage($langPrefix.'CREATE_CATEGORY'),
    "ICON" => ($boolBtnNew ? "" : "btn_new"),
    "LINK" => '/bitrix/admin/kit_crosssell_category.php?' .implode('&', $arrLink) . '&TYPE=CROSSSELL'
);

if($toShowGenAllBtn) {
    ?>
        <script>
            $(document).ready(function() {
                $(".adm-toolbar-panel-align-right:first").append(
                    `
                        <button class="ui-btn ui-btn-primary" id="GENERATE_ALL">
                            <?=Loc::getMessage($langPrefix.'GEN_ALL')?>
                        </button>
                    `
                );
                $("#GENERATE_ALL").on('click', () => {
                    var answer = confirm("<?=Loc::getMessage($langPrefix.'CONFIRM')?>");
                    if (answer)
                    {
                        $('body').append(`
                             <div class="overlay">
                                <div class="overlay__inner">
                                    <div class="overlay__content"><span class="spinner"></span></div>
                                </div>
                            </div>
                        `);
                        $.ajax({
                            url: "/bitrix/admin/ajax_handler.php",
                            type: "post",
                            data: { gen : 'all'} ,
                            success: function (response) {
                                // you will get response from your php page (what you echo or print)
                                $(".spinner").fadeOut(1000, () => {
                                    $(".overlay").fadeOut(100, () => {
                                        $( "div" ).remove( ".overlay" );
                                        location.reload();
                                    });
                                });
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.log(textStatus, errorThrown);
                            }
                        });
                    }
                });

            });
        </script>
    <?
}

if($APPLICATION->GetGroupRight('kit.crosssell') == "W")
    $lAdmin->AddAdminContextMenu($aContext, false, false);

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