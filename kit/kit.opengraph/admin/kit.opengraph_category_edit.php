<?
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type;
use Kit\Opengraph\OpengraphPageMetaTable;
use Kit\Opengraph\OpengraphCategoryTable;
use Kit\Opengraph\Helper\OpengraphHelper;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
$langPrefix = 'KIT_OPENGRAPH_';

if($APPLICATION->GetGroupRight('kit.opengraph') == "D")
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

if(!Loader::includeModule('kit.opengraph'))
    die;

if(isset($_REQUEST['refresh'])) {
    LocalRedirect("/bitrix/admin/kit.opengraph_settings.php?ENTITY_ID=".$_REQUEST['ENTITY_ID']."&lang=".LANG);
}

$errors = array();
global $APPLICATION;
//-----------------------------------------------------------------------------------------------------------------------------------------------
//  save options
if((isset($_REQUEST['save']) && !empty($_REQUEST['save']) || (isset($_REQUEST['apply']) && !empty($_REQUEST['apply'])))) {
    $arFields = array_filter(array_keys(OpengraphCategoryTable::getmap()), function($val) {
        return $val != 'ID';
    });
    $arFields = array_intersect_key($_REQUEST, array_flip($arFields));
    
    if(!empty($_REQUEST['CATEGORY_ID']))
        $arFields['PARENT_ID'] = intval($_REQUEST['CATEGORY_ID']);
    
    $arFields['SITE_ID'] = $_REQUEST['site'];
    
    if(isset($_REQUEST['ID'])) {
        $arFields['TIMESTAMP_X'] = new Type\DateTime( date( 'Y-m-d H:i:s' ), 'Y-m-d H:i:s' );
        $result = OpengraphCategoryTable::update(intval($_REQUEST['ID']), $arFields);
    } else
        $result = OpengraphCategoryTable::Add($arFields);
    if($result->isSuccess()) {
        if(isset($_REQUEST['save']) && !empty($_REQUEST['save'])) {
            LocalRedirect('/bitrix/admin/kit.opengraph_url_list.php?lang='.SITE_ID.'&site='.$_REQUEST['site']);
        }
        else {
            $_SESSION['SAVE_OPENGRAPH'][$result->getId()] = true;
            LocalRedirect($APPLICATION->GetCurPageParam("ID=".$result->getId(), array("ID")));
        }
    }
    else {
        $errors = $result->getErrorMessages();
    }
}
$arFields = array();
if($_REQUEST['ID']) {
    $arFields = OpengraphCategoryTable::getList(array(
        'filter' => array('ID' => intval($_REQUEST['ID'])),
        //        'cache' => array('ttl' => 2600)
    ))->fetch();
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
//-----------------------------------------------------------------------------------------------------------------------------------------------
// if necessary that show message error or success
if(isset($_SESSION['SAVE_OPENGRAPH'][intval($_REQUEST['ID'])])) {
    unset($_SESSION['SAVE_OPENGRAPH'][intval($_REQUEST['ID'])]);
    CAdminMessage::ShowMessage(array(
        "MESSAGE" => Loc::getMessage("KIT_OPENGRAPH_SUCCESS_SAVE"),
        "TYPE" => "OK"
    ));
}
else if(!empty($errors))
    CAdminMessage::ShowMessage(implode('<br>', $errors));

Loc::loadMessages(__FILE__);
//-----------------------------------------------------------------------------------------------------------------------------------------------
CJSCore::Init(array("jquery"));
?>
    <link rel="stylesheet" href="/bitrix/css/kit.opengraph/style.css">
    <script type="text/javascript" src="/bitrix/js//kit.opengraph/script.js"></script>
<?
$aTabs = array(
    array(
        "DIV" => "general_tab",
        "TAB" => Loc::getMessage("KIT_OPENGRAPH_GENERAL_EDIT"),
        "ICON" => "main_user_edit",
        "TITLE" => Loc::getMessage("KIT_OPENGRAPH_GENERAL_TITLE")
    ),
);

$aMenu= array(array(
                  "TEXT" => GetMessage( "KIT_OPENGRAPH_EDIT_BACK" ),
                  "TITLE" => GetMessage( "KIT_OPENGRAPH_EDIT_BACK_TITLE" ),
                  "ICON" => "btn_list",
                  'MENU' =>array(
                      array(
                          "TEXT" => GetMessage( "KIT_OPENGRAPH_EDIT_LIST" ),
                          "TITLE" => GetMessage( "KIT_OPENGRAPH_EDIT_LIST_TITLE" ),
                          "LINK" => '/bitrix/admin/kit.opengraph_url_list.php?lang='.SITE_ID.(isset($_REQUEST['site']) ? '&site='.$_REQUEST['site'] : ''),
                          "ICON" => "btn_list"
                      ),
                      array(
                          "TEXT" => GetMessage( "KIT_OPENGRAPH_EDIT_SECTION_BACK" ),
                          "TITLE" => GetMessage( "KIT_OPENGRAPH_EDIT_SECTION_BACK_TITLE" ),
                          "LINK" => '/bitrix/admin/kit.opengraph_url_list.php?lang='.SITE_ID.(isset($_REQUEST['site']) ? '&site='.$_REQUEST['site'] : '').(isset($_REQUEST['category']) ? '&category='.$_REQUEST['category'] : ''),
                      )
                  )),
);

$context = new CAdminContextMenu( $aMenu );
$context->Show();
$tabControl = new CAdminForm("tabControl", $aTabs);
$tabControl->Begin();
$tabControl->BeginNextFormTab();
$tabControl->AddSection("OG_COMMON_SETTINGS", GetMessage($langPrefix."SETTINGS"));
$tabControl->AddEditField("NAME", GetMessage($langPrefix.'CATEGORY_NAME'), false, false, $arFields['NAME']);
$tabControl->AddEditField("SORT", GetMessage($langPrefix.'SORT'), false, false, $arFields['SORT']);

if($APPLICATION->GetGroupRight('kit.opengraph') == "W")
    $tabControl->Buttons($arButtonsParams);

$tabControl->arParams["FORM_ACTION"] = $APPLICATION->GetCurPageParam();
$tabControl->Show();
$tabControl->ShowWarnings($tabControl->GetName(), $message);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>