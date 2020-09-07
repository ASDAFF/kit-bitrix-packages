<?
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type;
use Kit\Opengraph\OpengraphPageMetaTable;
use Kit\Opengraph\Helper\OpengraphHelper;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

if($APPLICATION->GetGroupRight('kit.opengraph') == "D")
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$langPrefix = 'KIT_OPENGRAPH_';
//\Bitrix\Main\EventManager::getInstance()->registerEventHandler("main", "OnBeforeProlog", 'kit.opengraph', '\Kit\PrivatePrice\EventHandlers', 'OnBeforeProlog');
if(!Loader::includeModule('kit.opengraph'))
    die;
if(isset($_REQUEST['refresh'])) {
    LocalRedirect("/bitrix/admin/kit.opengraph_settings.php?ENTITY_ID=".$_REQUEST['ENTITY_ID']."&lang=".LANG);
}
$id_module = 'kit.opengraph';
$errors = array();
global $APPLICATION;
//-----------------------------------------------------------------------------------------------------------------------------------------------
//  save options
if((isset($_REQUEST['save']) && !empty($_REQUEST['save']) || (isset($_REQUEST['apply']) && !empty($_REQUEST['apply'])))) {
    $arFields = array_filter(array_keys(OpengraphPageMetaTable::getmap()), function($val) {
        return $val != 'ID';
    });
    $arFields = array_intersect_key($_REQUEST, array_flip($arFields));
    if(!isset($arFields['ACTIVE_TW']))
        $arFields['ACTIVE_TW'] = 'N';
    if(!isset($arFields['ACTIVE_OG']))
        $arFields['ACTIVE_OG'] = 'N';
    $arFields['OG_IMAGE'] = OpengraphHelper::saveImage($arFields['OG_IMAGE']);
    $arFields['TW_IMAGE'] = OpengraphHelper::saveImage($arFields['TW_IMAGE'], 'TW_IMAGE');
    $arFields['OG_PROPS_ACTIVE'] = serialize($arFields['OG_PROPS_ACTIVE']);
    $arFields['TW_PROPS_ACTIVE'] = serialize($arFields['TW_PROPS_ACTIVE']);
    $arFields['SITE_ID'] = $_REQUEST['site'];
    
    if(!isset($arFields['NAME']) || empty($arFields['NAME']))
    {
        $errors = array(Loc::getMessage($langPrefix.'REQUIRED_FIELD_NAME'));
    }
   
    if(empty($errors)) {
        $option = \CUserOptions::getOption("main.ui.filter", 'tbl_page_list', array());
        $arr = $option['filters']['tmp_filter']['fields'];
        $arr = array_filter($arr, function($v, $k) {
            return !empty($v) && $k == 'CATEGORY_ID';
        }, ARRAY_FILTER_USE_BOTH);
        if(isset($arr['CATEGORY_ID']))
            $arFields['CATEGORY_ID'] = $arr['CATEGORY_ID'];
        if(isset($_REQUEST['ID'])) {
            $arFields['TIMESTAMP_X'] = new Type\DateTime(date('Y-m-d H:i:s'), 'Y-m-d H:i:s');
            $result = OpengraphPageMetaTable::update(intval($_REQUEST['ID']), $arFields);
        }
        else
            $result = OpengraphPageMetaTable::Add($arFields);
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
}
$arFields = array();
if($_REQUEST['ID']) {
    $arFields = OpengraphPageMetaTable::getList(array(
        'filter' => array('ID' => intval($_REQUEST['ID'])),
        //        'cache' => array('ttl' => 2600)
    ))->fetch();
    $arFields['OG_PROPS_ACTIVE'] = unserialize($arFields['OG_PROPS_ACTIVE']);
    $arFields['TW_PROPS_ACTIVE'] = unserialize($arFields['TW_PROPS_ACTIVE']);
} else if(!empty($_POST)) {
    $arFields = htmlspecialcharsbx($_POST);
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
        "TAB" => Loc::getMessage($langPrefix."GENERAL_EDIT"),
        "ICON" => "main_user_edit",
        "TITLE" => Loc::getMessage($langPrefix."GENERAL_TITLE")
    ),
    array(
        "DIV" => "og_tab",
        "TAB" => Loc::getMessage("KIT_OPENGRAPH_OG_EDIT"),
        "ICON" => "main_user_edit",
        "TITLE" => Loc::getMessage("KIT_OPENGRAPH_OG_TITLE")
    ),
    array(
        "DIV" => "twit_tab",
        "TAB" => Loc::getMessage("KIT_OPENGRAPH_TWIT_EDIT"),
        "ICON" => "main_user_edit",
        "TITLE" => Loc::getMessage("KIT_OPENGRAPH_TWIT_TITLE")
    ),
);
$ruleOrder = array(
        'START_PAGE' => GetMessage($langPrefix."START_PAGE"),
        'END_PAGE' => GetMessage($langPrefix."END_PAGE"),
);
$aMenu = array(
    array(
        "TEXT" => GetMessage( "KIT_OPENGRAPH_EDIT_BACK" ),
        "TITLE" => GetMessage( "KIT_OPENGRAPH_EDIT_BACK_TITLE" ),
        "ICON" => "btn_list",
        "LINK" => '/bitrix/admin/kit.opengraph_url_list.php?lang='.SITE_ID.(isset($_REQUEST['site']) ? '&site='.$_REQUEST['site'] : '').(isset($_REQUEST['CATEGORY_ID']) ? '&CATEGORY_ID='.$_REQUEST['CATEGORY_ID'] : ''),
    ),);
$context = new CAdminContextMenu( $aMenu );
$context->Show();
$tabControl = new CAdminForm("tabControl", $aTabs);
$tabControl->Begin();
$tabControl->BeginNextFormTab();
$tabControl->AddSection("OG_COMMON_SETTINGS", GetMessage($langPrefix."SETTINGS"));
$tabControl->AddEditField("NAME", GetMessage($langPrefix.'NAME'), false, false, $arFields['NAME']);
$tabControl->AddEditField("SORT", GetMessage($langPrefix.'SORT'), false, false, (isset($arFields['SORT'])) ? $arFields['SORT'] : 100 );
$tabControl->AddDropDownField("ORDER", GetMessage($langPrefix."ORDER"), false, $ruleOrder, isset($arFields['ORDER']) ? $arFields['ORDER'] : false, array("style='width:153px;'"));

$tabControl->BeginNextFormTab();
$tabControl->AddSection("OG_COMMON_SETTINGS", GetMessage($langPrefix."SETTINGS"));
$tabControl->BeginCustomField("ACTIVE_OG", GetMessage($langPrefix."ACTIVE_OG"), false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center;">
            <?=GetMessage($langPrefix."ACTIVE_OG");?>
            <input type="checkbox" name="ACTIVE_OG" value="Y"<?if($arFields['ACTIVE_OG'] == 'Y'):?> checked="checked"<?endif;?> >
        </td>
    </tr>
<?
$tabControl->EndCustomField("ACTIVE_OG");
include("block/og_settings.php");

$tabControl->BeginNextFormTab();
$tabControl->AddSection("TW_COMMON_SETTINGS", GetMessage($langPrefix."SETTINGS"));
$tabControl->BeginCustomField("ACTIVE_TW", GetMessage($langPrefix."ACTIVE_TW"), false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center;">
            <?=GetMessage($langPrefix."ACTIVE_TW");?>
            <input type="checkbox" name="ACTIVE_TW" value="Y"<?if($arFields['ACTIVE_TW'] == 'Y'):?> checked="checked"<?endif;?>>
        </td>
    </tr>
<?
$tabControl->EndCustomField("ACTIVE_TW");
include("block/tw_settings.php");

if($APPLICATION->GetGroupRight('kit.opengraph') == "W")
    $tabControl->Buttons($arButtonsParams);

$tabControl->arParams["FORM_ACTION"] = $APPLICATION->GetCurPageParam();
$tabControl->Show();
$tabControl->ShowWarnings($tabControl->GetName(), $message);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>