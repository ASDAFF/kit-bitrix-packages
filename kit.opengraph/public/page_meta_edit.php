<?
use Bitrix\Main\Localization\Loc;
use Kit\Opengraph\OpengraphPageMetaTable;
use Kit\Opengraph\Helper\OpengraphHelper;
define('BX_PUBLIC_MODE', 0);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_js.php");
$langPrefix = 'KIT_OPENGRAPH_';

$addUrl = 'lang='.LANGUAGE_ID.($logical == "Y"?'&logical=Y':'');
$useEditor3 = COption::GetOptionString('fileman', "use_editor_3", "N") == "Y";
$bFromComponent = $_REQUEST['from'] == 'main.include' || $_REQUEST['from'] == 'includefile' || $_REQUEST['from'] == 'includecomponent';
$bDisableEditor = !CModule::IncludeModule('fileman') || ($_REQUEST['noeditor'] == 'Y');

if (!($USER->CanDoOperation('fileman_admin_files') || $USER->CanDoOperation('fileman_edit_existent_files')))
{
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/fileman/include.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.opengraph/include.php");

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/fileman/admin/fileman_html_edit.php");
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/public/file_edit.php");

$obJSPopup = new CJSPopup("lang=".urlencode($_GET["lang"])."&site=".urlencode($_GET["site"])."&back_url=".urlencode($_GET["back_url"])."&path=".urlencode($_GET["path"])."&name=".urlencode($_GET["name"]), array("SUFFIX"=>($_REQUEST['subdialog'] == 'Y'? 'editor':'')));

$strWarning = "";
$site_template = false;
$rsSiteTemplates = CSite::GetTemplateList($site);
while($arSiteTemplate = $rsSiteTemplates->Fetch())
{
    if(strlen($arSiteTemplate["CONDITION"])<=0)
    {
        $site_template = $arSiteTemplate["TEMPLATE"];
        break;
    }
}

$io = CBXVirtualIo::GetInstance();

$bVarsFromForm = false;	// if 'true' - we will get content  and variables from form, if 'false' - from saved file
$bSessIDRefresh = false;	// ����, �����������, ����� �� ��������� �� ������ �� �������
$editor_name = (isset($_REQUEST['editor_name'])? $_REQUEST['editor_name'] : 'filesrc_pub');

if (strlen($filename) > 0 && ($mess = CFileMan::CheckFileName($filename)) !== true)
{
    $filename2 = $filename;
    $filename = '';
    $strWarning = $mess;
    $bVarsFromForm = true;
}

$path = urldecode($path);
$path = $io->CombinePath("/", $path);

$site = CFileMan::__CheckSite($site);
if(!$site)
    $site = CSite::GetSiteByFullPath($_SERVER["DOCUMENT_ROOT"].$path);

$DOC_ROOT = CSite::GetSiteDocRoot($site);
$abs_path = $DOC_ROOT.$path;

if(GetFileType($abs_path) == "IMAGE")
    $strWarning = GetMessage("PUBLIC_EDIT_FILE_IMAGE_ERROR");

$arPath = Array($site, $path);

if(!$io->FileExists($abs_path) && !$io->DirectoryExists($abs_path))
{
    $p = strrpos($path, "/");
    if($p!==false)
    {
        $new = "Y";
        $filename = substr($path, $p+1);
        $path = substr($path, 0, $p);
    }
}
$relPath = $io->ExtractPathFromPath($path);

$NEW_ROW_CNT = 1;

$arParsedPath = CFileMan::ParsePath(Array($site, $path), true, false, "", false);
$isScriptExt = in_array(CFileman::GetFileExtension($path), CFileMan::GetScriptFileExt());

if (CAutoSave::Allowed())
    $AUTOSAVE = new CAutoSave();

$imgName = $filename;
if ($filename == '')
    $imgName = $io->ExtractNameFromPath($path);
else
    $imgName = $filename;
$imgName = GetFileNameWithoutExtension($imgName).'-img';

//Check access to file
if(
    (
        strlen($new) > 0 &&
        !(
            $USER->CanDoOperation('fileman_admin_files') &&
            $USER->CanDoFileOperation('fm_create_new_file', $arPath)
        )
    )
    ||
    (
        strlen($new) < 0 &&
        !(
            $USER->CanDoOperation('fileman_edit_existent_files') &&
            $USER->CanDoFileOperation('fm_edit_existent_file',$arPath)
        )
    )
)
{
    $strWarning = GetMessage("ACCESS_DENIED");
}


///////////save
if($_SERVER["REQUEST_METHOD"] == "POST" && $_REQUEST['save'] == 'Y')
{
    if(!check_bitrix_sessid())
    {
        $strWarning = GetMessage("FILEMAN_SESSION_EXPIRED");
        $bVarsFromForm = true;
        $bSessIDRefresh = true;
    }
    else
    {
    
    }
    
    if(strlen($strWarning) <= 0)
    {
        // save
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

        if(isset($_REQUEST['ID']) && !empty($_REQUEST['ID'])) {
            $arFields['TIMESTAMP_X'] = ConvertDateTime(date('Y-m-d H:i:s'), 'Y-m-d H:i:s');
            $result = OpengraphPageMetaTable::update(intval($_REQUEST['ID']), $arFields);
        }
        else
            $result = OpengraphPageMetaTable::Add($arFields);
            
        if(!$result->isSuccess()) {
            $strWarning = current($result->getErrorMessages());
        }
    }
    
    if(strlen($strWarning) <= 0)
    {
        if ($arUndoParams)
            CUndo::ShowUndoMessage(CUndo::Add($arUndoParams));
        
        ?>
        <script>
            <?
            if($_REQUEST['subdialog'] != 'Y'):
            $url = $_REQUEST["back_url"];
            if(substr($url, 0, 1) != "/" || substr($url, 1, 1) == "/")
            {
                //only local /url is allowed
                $url = '';
            }
            ?>
            top.BX.reload('<?=CUtil::JSEscape($url)?>', true);
            <?else:?>
            if (null != top.structReload)
                top.structReload('<?=urlencode($_REQUEST["path"])?>');
            <?endif;?>
            top.<?=$obJSPopup->jsPopup?>.Close();
        </script>
        <?
    }
    else
    {
        ?>
        <script>
            top.CloseWaitWindow();
            top.<?=$obJSPopup->jsPopup?>.ShowError('<?=CUtil::JSEscape($strWarning)?>');
            var pMainObj = top.GLOBAL_pMainObj['<?=CUtil::JSEscape($editor_name)?>'];
            pMainObj.Show(true);
            <?if ($bSessIDRefresh):?>
            top.BXSetSessionID('<?=CUtil::JSEscape(bitrix_sessid())?>');
            <?endif;?>
        </script>
        <?
    }
    die();
}

$obJSPopup->ShowTitlebar(GetMessage('PUBLIC_EDIT_TITLE_OPENGRAPH'));


$obJSPopup->StartContent(
    array(
        'style' => "0px; height: 500px; overflow: hidden;",
        'class' => "bx-content-editor"
    )
);
?>
    </form>
    <iframe src="javascript:void(0)" name="file_edit_form_target" height="0" width="0" style="display: none;"></iframe>
    <form action="/bitrix/admin/public_kit_opengraph_meta_edit.php" name="editor_form" method="post" enctype="multipart/form-data" target="file_edit_form_target" style="margin: 0px; padding: 0px; ">
        <?
        if (CAutoSave::Allowed())
        {
            echo CJSCore::Init(array('autosave'), true);
            $AUTOSAVE->Init();
            ?><script type="text/javascript">BX.WindowManager.Get().setAutosave();</script><?
        }
        
        
        $urlShort = GetPagePath(str_replace(array(OpengraphCore::getHttpSchema().'://'.$_SERVER['SERVER_NAME']), '', $_SERVER['HTTP_REFERER']), false);
        
        $arFields = array(
            'SORT' => 100,
            'NAME' => $urlShort
        );

        $core = new OpengraphCore();
        $rs = $core->getRulePathExact($urlShort, $_REQUEST['site']);
        $arRuleByPath = $rs->fetch();

        if(is_array($arRuleByPath)) {
            $arFields = array_merge($arFields, $arRuleByPath);
            $arFields['OG_PROPS_ACTIVE'] = unserialize($arFields['OG_PROPS_ACTIVE']);
            $arFields['TW_PROPS_ACTIVE'] = unserialize($arFields['TW_PROPS_ACTIVE']);
        }
        ?>
        <?=bitrix_sessid_post()?>
        <input type="submit" name="submitbtn" style="display: none;" />
        <input type="hidden" name="mode" id="mode" value="public" />
        <input type="hidden" name="save" id="save" value="Y" />
        <input type="hidden" name="site" id="site" value="<?=htmlspecialcharsbx($site)?>" />
        <input type="hidden" name="subdialog" value="<?echo htmlspecialcharsbx($_REQUEST['subdialog'])?>" />
        <?if (is_set($_REQUEST, 'back_url')):?>
            <input type="hidden" name="back_url" value="<?=htmlspecialcharsbx($_REQUEST['back_url'])?>" />
        <?endif;?>
        <?if (isset($arFields['ID']) && !empty($arFields['ID'])):?>
            <input type="hidden" name="ID" value="<?=$arFields['ID'];?>" />
        <?endif;?>
        <?if(!$bEdit):?>
            <input type="hidden" name="new" id="new" value="Y" />
            <input type="hidden" name="filename" id="filename" value="<?echo htmlspecialcharsbx($filename)?>" />
            <input type="hidden" name="path" id="path" value="<?=htmlspecialcharsbx($path.'/'.$filename)?>" />
        <?else:?>
            <input type="hidden" name="title" value="<?=htmlspecialcharsbx($title)?>" />
            <input type="hidden" name="path" id="path" value="<?=htmlspecialcharsbx($path)?>" />
        <?endif;?>

        <script>
            <?=$obJSPopup->jsPopup?>.PARTS.CONTENT.getElementsByTagName('FORM')[0].style.display = 'none'; // hack

            function BXFormSubmit()
            {
                ShowWaitWindow();
                var obForm = document.forms.editor_form;
                obForm.elements.submitbtn.click();
            }

            function BXSetSessionID(new_sessid)
            {
                document.forms.editor_form.sessid.value = new_sessid;
            }
        </script>
        
        <?
       /////////////////////////////// form inputs start

        Loc::loadMessages(__FILE__);
        //-----------------------------------------------------------------------------------------------------------------------------------------------
        CJSCore::Init(array("jquery"));
        ?>
        <link rel="stylesheet" href="/bitrix/css/kit.opengraph/style.css">
        <link rel="stylesheet" href="/bitrix/panel/main/admin.css">
        <script type="text/javascript" src="/bitrix/js/kit.opengraph/script.js"></script>
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
        $context = new CAdminContextMenu( array() );
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
        include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.opengraph/admin/block/og_settings.php");
    
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
        include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.opengraph/admin/block/tw_settings.php");
        $tabControl->Buttons(array(
                'btnSave' => false,
                'btnApply' => false,
                'btnCancel' => false
        ));
        $tabControl->arParams["FORM_ACTION"] = $APPLICATION->GetCurPageParam();
        $tabControl->Show();
        $tabControl->ShowWarnings($tabControl->GetName(), $message);
        
       /////////////////////////////// form inputs end
        
        
        
        
        
        $obJSPopup->StartButtons();
        ?>
        <input type="button" class="adm-btn-save" id="btn_popup_save" name="btn_popup_save" value="<?=GetMessage("JSPOPUP_SAVE_CAPTION")?>" onclick="BXFormSubmit();" title="<?=GetMessage("JSPOPUP_SAVE_CAPTION")?>" />
<?
$obJSPopup->ShowStandardButtons(array('cancel'));
$obJSPopup->EndButtons();

if (CAutoSave::Allowed())
{
    $AUTOSAVE->checkRestore();
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin_js.php");
?>