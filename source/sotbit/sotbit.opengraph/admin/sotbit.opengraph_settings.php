<?

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
$langPrefix = 'SOTBIT_OPENGRAPH_';
//\Bitrix\Main\EventManager::getInstance()->registerEventHandler("main", "OnBeforeProlog", 'sotbit.opengraph', '\Sotbit\PrivatePrice\EventHandlers', 'OnBeforeProlog');
if(!Loader::includeModule('sotbit.opengraph'))
    die;
if(isset($_REQUEST['refresh'])) {
    LocalRedirect("/bitrix/admin/sotbit.opengraph_settings.php?ENTITY_ID=".$_REQUEST['ENTITY_ID']."&lang=".LANG);
}
$errors = array();
//-----------------------------------------------------------------------------------------------------------------------------------------------
//  save options
if((isset($_REQUEST['save']) && !empty($_REQUEST['save']) || (isset($_REQUEST['apply']) && !empty($_REQUEST['apply'])))) {
    
    $_REQUEST['EXCEPTION_URL'] = array_filter($_REQUEST['EXCEPTION_URL'], function($val) {
        return !empty(trim($val));
    });
    $save = true;
    $arFields = array_filter(array_keys(\Sotbit\Opengraph\OpengraphPageMetaTable::getmap()), function($val) {
        return $val != 'ID';
    });
    $arFields = array_intersect_key($_REQUEST, array_flip($arFields));
    if(!isset($arFields['ACTIVE_TW']))
        $arFields['ACTIVE_TW'] = 'N';
    if(!isset($arFields['ACTIVE_OG']))
        $arFields['ACTIVE_OG'] = 'N';
    $arFields['OG_IMAGE'] = \Sotbit\Opengraph\Helper\OpengraphHelper::saveImage($arFields['OG_IMAGE']);
    $arFields['TW_IMAGE'] = \Sotbit\Opengraph\Helper\OpengraphHelper::saveImage($arFields['TW_IMAGE'], 'TW_IMAGE');
    $arFields['OG_PROPS_ACTIVE'] = serialize($arFields['OG_PROPS_ACTIVE']);
    $arFields['TW_PROPS_ACTIVE'] = serialize($arFields['TW_PROPS_ACTIVE']);
    $arFields['SITE_ID'] = $_REQUEST['site'];
    $arFields['EXCEPTION_URL'] = $_REQUEST['EXCEPTION_URL'];
    
    $metaTable = new \Sotbit\Opengraph\OpengraphPageMetaTable();
    $checkRequeredFields = $metaTable->checkRuleToSave($arFields);
    
    if($checkRequeredFields) {
        Bitrix\Main\Config\Option::set(SotbitOpengraph::MODULE_ID, 'OPENGRAPH_SETTINGS', serialize($arFields), $arFields['SITE_ID']);
    } else {
        $errors = $metaTable->getErrors();
    }
    
    OpengraphSettings::getInstance()->refreshOptions();
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

//-----------------------------------------------------------------------------------------------------------------------------------------------
// if necessary that show message error or success
if($save) {
    if(!empty($errors)) {
        CAdminMessage::ShowMessage(implode('<br>', $errors));
    }
    else
        CAdminMessage::ShowMessage(array(
            "MESSAGE" => Loc::getMessage("SOTBIT_OPENGRAPH_SUCCESS_SAVE"),
            "TYPE" => "OK"
        ));
}
if($APPLICATION->GetGroupRight('sotbit.opengraph') == "D")
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
Loc::loadMessages(__FILE__);

//-----------------------------------------------------------------------------------------------------------------------------------------------
// get lists and other data for showing settings
if(!empty($_POST)) {
    
    if(isset($_POST['OG_IMAGE_del']) && $_POST['OG_IMAGE_del'] == 'Y') {
        unset($_POST['OG_IMAGE_del'],$_POST['OG_IMAGE']);
    }
    
    if(isset($_POST['TW_IMAGE_del']) && $_POST['TW_IMAGE_del'] == 'Y') {
        unset($_POST['TW_IMAGE_del'],$_POST['TW_IMAGE']);
    }
    
    $arFields = $_POST;
}
else {
    $arFields = OpengraphSettings::getInstance($_REQUEST['site'])->getOptions();
    $arFields['OG_PROPS_ACTIVE'] = unserialize($arFields['OG_PROPS_ACTIVE']);
    $arFields['TW_PROPS_ACTIVE'] = unserialize($arFields['TW_PROPS_ACTIVE']);
}

//-----------------------------------------------------------------------------------------------------------------------------------------------
CJSCore::Init(array("jquery"));
?>
    <link rel="stylesheet" href="/bitrix/css/sotbit.opengraph/style.css">
    <script type="text/javascript" src="/bitrix/js//sotbit.opengraph/script.js"></script>
<?
$aTabs = array(
    array(
        "DIV" => "general_tab",
        "TAB" => Loc::getMessage("SOTBIT_OPENGRAPH_GENERAL_EDIT"),
        "ICON" => "main_user_edit",
        "TITLE" => Loc::getMessage("SOTBIT_OPENGRAPH_GENERAL_TITLE")
    ),
    array(
        "DIV" => "og_tab",
        "TAB" => Loc::getMessage("SOTBIT_OPENGRAPH_OG_EDIT"),
        "ICON" => "main_user_edit",
        "TITLE" => Loc::getMessage("SOTBIT_OPENGRAPH_OG_TITLE")
    ),
    array(
        "DIV" => "twit_tab",
        "TAB" => Loc::getMessage("SOTBIT_OPENGRAPH_TWIT_EDIT"),
        "ICON" => "main_user_edit",
        "TITLE" => Loc::getMessage("SOTBIT_OPENGRAPH_TWIT_TITLE")
    ),
);

$tabControl = new CAdminForm("tabControl", $aTabs);
$tabControl->Begin();
$tabControl->BeginNextFormTab();
$tabControl->AddSection("EXCEPTIONS_URL", GetMessage($langPrefix."EXCEPTIONS_URL"));
$tabControl->BeginCustomField("EXCEPTION_URL", GetMessage($langPrefix."EXCEPTION_URL"), false);
if(isset($arFields['EXCEPTION_URL']) && is_array($arFields['EXCEPTION_URL']))
    foreach($arFields['EXCEPTION_URL'] as $index => $url) {
        ?>
        <tr class="adm-detail-file-row">
            <td><? echo $tabControl->GetCustomLabelHTML() ?></td>
            <td>
                <input type="text" class="exceptions" name="EXCEPTION_URL[]" value="<?=$url;?>">
            </td>
        </tr>
        <?
    }
$tabControl->EndCustomField("EXCEPTION_URL");
$tabControl->addEditField("EXCEPTION_URL[]", "", false);
$tabControl->BeginCustomField("EXCEPTION_BUTTON", GetMessage($langPrefix."EXCEPTION_BUTTON"), false);
?>
    <tr class="adm-detail-file-row">
        <td><? echo $tabControl->GetCustomLabelHTML() ?></td>
        <td>
            <input type="button" class="add_exception" value="<?=GetMessage($langPrefix."ADD_BUTTON_NAME");?>">
        </td>
    </tr>
<?
$tabControl->EndCustomField("EXCEPTION_BUTTON");
$tabControl->BeginCustomField("EXCEPTION_NOTE", GetMessage($langPrefix."EXCEPTION_NOTE"), false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align:  center;">
            <div class="adm-info-message-wrap">
                <div class="adm-info-message">
                    <?=Loc::getMessage("SOTBIT_OPENGRAPH_EXCEPTION_NOTE");?>
                </div>
            </div>
        </td>
    </tr>
<?
$tabControl->EndCustomField("EXCEPTION_NOTE");
$tabControl->BeginNextFormTab();

$tabControl->AddSection("OG_DEFAULT_VALUES", GetMessage($langPrefix."DEFAULT_VALUES"));
$tabControl->BeginCustomField("OG_TYPE_DEFAULT", GetMessage($langPrefix."OG_TYPE"), false);

$TYPES = array(
    'REFERENCE_ID' => array('product', 'article'),
    'REFERENCE' => array('product', 'article')
);

?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <div class="inline name">
                <?=GetMessage($langPrefix."OG_TYPE_NAME")?>
            </div>
            <div class="inline value">
                <!--                <input name="OG_TYPE" value="--><?//=$arFields['OG_TYPE'];?><!--">-->
                <?=SelectBoxFromArray( 'OG_TYPE', $TYPES, $arFields['OG_TYPE'], '', 'style="width:147px;"')?>
            </div>
            <div class="inline checkbox">
                <input type="hidden" name="OG_PROPS_ACTIVE[OG_TYPE]" value="1">
            </div>
        </td>
    </tr>
<?
$tabControl->EndCustomField("OG_TYPE_DEFAULT");
$tabControl->BeginCustomField("OG_TITLE_DEFAULT", GetMessage($langPrefix."OG_OGP_TITLE"), false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <div class="inline name">
                <?=GetMessage($langPrefix."OG_TITLE_NAME")?>
            </div>
            <div class="inline value">
                <input name="OG_TITLE" value="<?=$arFields['OG_TITLE'];?>">
            </div>
            <div class="inline checkbox">
                <input type="hidden" name="OG_PROPS_ACTIVE[OG_TITLE]" value="1">
            </div>
        </td>
    </tr>
<?
$tabControl->EndCustomField("OG_TITLE_DEFAULT");
$tabControl->BeginCustomField("OG_DESCRIPTION_DEFAULT", GetMessage($langPrefix."OG_DESCRIPTION"), false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <div class="inline name">
                <?=GetMessage($langPrefix."OG_DESCRIPTION_NAME")?>
            </div>
            <div class="inline value">
                <input name="OG_DESCRIPTION" value="<?=$arFields['OG_DESCRIPTION'];?>">
            </div>
            <div class="inline checkbox">
                <input type="hidden" name="OG_PROPS_ACTIVE[OG_DESCRIPTION]" value="1">
            </div>
        </td>
    </tr>
<?
$tabControl->EndCustomField("OG_DESCRIPTION_DEFAULT");
$tabControl->BeginCustomField("OG_IMAGE_SECURE_URL_DEFAULT", GetMessage($langPrefix."OG_IMAGE_SECURE_URL"), false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <div class="inline name">
                <?=GetMessage($langPrefix."OG_IMAGE_SECURE_URL_NAME")?>
            </div>
            <div class="inline value">
                <input name="OG_IMAGE_SECURE_URL"
                       value="<?=$arFields['OG_IMAGE_SECURE_URL'];?>">
            </div>
            <div class="inline checkbox">
                <input type="hidden" name="OG_PROPS_ACTIVE[OG_IMAGE_SECURE_URL]" value="1">
            </div>
        </td>
    </tr>
<?
$tabControl->EndCustomField("OG_IMAGE_SECURE_URL_DEFAULT");
$tabControl->BeginCustomField("OG_DEFAULT_IMAGE", GetMessage($langPrefix."IMAGE"), false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center;">
            <span style="vertical-align: middle;">
            <? echo $tabControl->GetCustomLabelHTML() ?>
                <input type="hidden" name="OG_PROPS_ACTIVE[OG_IMAGE]" value="1">
                </span>
            <div style="display: inline-block; vertical-align: middle;">
                <? echo \Bitrix\Main\UI\FileInput::createInstance(array(
                    "name" => "OG_IMAGE",
                    "description" => true,
                    "upload" => true,
                    "allowUpload" => "I",
                    "medialib" => true,
                    "fileDialog" => true,
                    "cloud" => true,
                    "delete" => true,
                    "maxCount" => 1
                ))->show(isset($arFields['OG_IMAGE']) ? $arFields['OG_IMAGE'] : false, $bVarsFromForm); ?>
            </div>
        </td>
    </tr>
<?
$tabControl->EndCustomField("OG_DEFAULT_IMAGE");

$tabControl->BeginNextFormTab();
$tabControl->AddSection("TW_DEFAULT_VALUES", GetMessage($langPrefix."DEFAULT_VALUES"));
$tabControl->BeginCustomField("TW_CARD_DEFAULT", GetMessage($langPrefix."TW_CARD"), false);

$TYPES = array(
    'REFERENCE_ID' => array('summary', 'summary_large_image'),
    'REFERENCE' => array('summary', 'summary_large_image')
);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <div class="inline name">
                <?=GetMessage($langPrefix."TW_CARD_NAME")?>
            </div>
            <div class="inline value">
                <?=SelectBoxFromArray( 'TW_CARD', $TYPES, $arFields['TW_CARD'], '', 'style="width:147px;"')?>
            </div>
            <div class="inline checkbox">
                <input type="hidden" name="TW_PROPS_ACTIVE[TW_CARD]" value="1">
            </div>
        </td>
    </tr>
<?
$tabControl->EndCustomField("TW_CARD_DEFAULT");
$tabControl->BeginCustomField("TW_TITLE_DEFAULT", GetMessage($langPrefix."TW_TITLE"), false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">

            <div class="inline name">
                <?=GetMessage($langPrefix."TW_TITLE_NAME")?>
            </div>
            <div class="inline value">
                <input name="TW_TITLE" value="<?=$arFields['TW_TITLE'];?>">
            </div>
            <div class="inline checkbox">
                <input type="hidden" name="TW_PROPS_ACTIVE[TW_TITLE]" value="1">
            </div>
        </td>
    </tr>
<?
$tabControl->EndCustomField("TW_TITLE_DEFAULT");
$tabControl->BeginCustomField("TW_SITE_DEFAULT", GetMessage($langPrefix."TW_SITE"), false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <div class="inline name">
                <?=GetMessage($langPrefix."TW_SITE_NAME")?>
            </div>
            <div class="inline value">
                <input name="TW_SITE" value="<?=$arFields['TW_SITE'];?>">
            </div>
            <div class="inline checkbox">
                <input type="hidden" name="TW_PROPS_ACTIVE[TW_SITE]" value="1">
            </div>
        </td>
    </tr>
<?
$tabControl->EndCustomField("TW_SITE_DEFAULT");
$tabControl->BeginCustomField("TW_CREATOR_DEFAULT", GetMessage($langPrefix."TW_CREATOR"), false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <div class="inline name">
                <?=GetMessage($langPrefix."TW_CREATOR_NAME")?>
            </div>
            <div class="inline value">
                <input name="TW_CREATOR" value="<?=$arFields['TW_CREATOR'];?>">
            </div>
            <div class="inline checkbox">
                <input type="hidden" name="TW_PROPS_ACTIVE[TW_CREATOR]" value="1">
            </div>
        </td>
    </tr>
<?
$tabControl->EndCustomField("TW_CREATOR_DEFAULT");
$tabControl->BeginCustomField("TW_DESCRIPTION_DEFAULT", GetMessage($langPrefix."TW_DESCRIPTION"), false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <div class="inline name">
                <?=GetMessage($langPrefix."TW_DESCRIPTION_NAME")?>
            </div>
            <div class="inline value">
                <input name="TW_DESCRIPTION" value="<?=$arFields['TW_DESCRIPTION']?>">
            </div>
            <div class="inline checkbox">
                <input type="hidden" name="TW_PROPS_ACTIVE[TW_DESCRIPTION]" value="1">
            </div>

        </td>
    </tr>
<?
$tabControl->EndCustomField("TW_DESCRIPTION_DEFAULT");
$tabControl->BeginCustomField("TW_IMAGE_ALT_DEFAULT", GetMessage($langPrefix."TW_IMAGE_ALT"), false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <div class="inline name">
                <?=GetMessage($langPrefix."TW_IMAGE_ALT_NAME")?>
            </div>
            <div class="inline value">
                <input name="TW_IMAGE_ALT" value="<?=$arFields['TW_IMAGE_ALT'];?>">
            </div>
            <div class="inline checkbox">
                <input type="hidden" name="TW_PROPS_ACTIVE[TW_IMAGE_ALT]" value="1">
            </div>
        </td>
    </tr>
<?
$tabControl->EndCustomField("TW_IMAGE_ALT_DEFAULT");
$tabControl->BeginCustomField("TW_DEFAULT_IMAGE", GetMessage($langPrefix."IMAGE"), false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center;">
            <span style="vertical-align: middle;">
            <? echo $tabControl->GetCustomLabelHTML() ?>
                <input type="hidden" name="TW_PROPS_ACTIVE[TW_IMAGE]" value="1">
                </span>
            <div style="display: inline-block; vertical-align: middle;">
                <? echo \Bitrix\Main\UI\FileInput::createInstance(array(
                    "name" => "TW_IMAGE",
                    "description" => true,
                    "upload" => true,
                    "allowUpload" => "I",
                    "medialib" => true,
                    "fileDialog" => true,
                    "cloud" => true,
                    "delete" => true,
                    "maxCount" => 1
                ))->show(isset($arFields['TW_IMAGE']) ? $arFields['TW_IMAGE'] : false, $bVarsFromForm); ?>
            </div>
        </td>
    </tr>
<?
$tabControl->EndCustomField("TW_DEFAULT_IMAGE");

if($APPLICATION->GetGroupRight('sotbit.opengraph') == "W")
    $tabControl->Buttons($arButtonsParams);

$tabControl->arParams["FORM_ACTION"] = $APPLICATION->GetCurPageParam();
$tabControl->Show();
$tabControl->ShowWarnings($tabControl->GetName(), $message);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>