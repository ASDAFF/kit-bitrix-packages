<?
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Type;
use \Kit\Crosssell\Orm\CrosssellCategoryTable;
use \Kit\Crosssell\Helper\crosssellaction;

//on page load set up//-----------------------------------------------------------------------------------------------------------------------------------------------
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
$langPrefix = 'KIT_CROSSSELL_';
if($APPLICATION->GetGroupRight('kit.crosssell') == "D")
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
if(!Loader::includeModule('kit.crosssell'))
    die;
$errors = array();
global $APPLICATION;

$event = new Bitrix\Main\Event("kit.crosssell", "OnCrosssellCategorySettingsStart");
$event->send();

//save options//-----------------------------------------------------------------------------------------------------------------------------------------------
if((isset($_REQUEST['save']) && !empty($_REQUEST['save']) || (isset($_REQUEST['apply']) && !empty($_REQUEST['apply'])))) {
    $arFields = array_filter(array_keys(CrosssellCategoryTable::getmap()), function($val) {
        return $val != 'ID';
    });
    $arFields = array_intersect_key($_REQUEST, array_flip($arFields));

    if(!empty($_REQUEST['CATEGORY_ID']))
        $arFields['PARENT_ID'] = intval($_REQUEST['CATEGORY_ID']);
    
    $arFields['SITE_ID'] = $_REQUEST['site'];

    $extraSettings = array();
    if (isset($_REQUEST['ITEM_TEMPLATE'])){
        if (is_string($_REQUEST['ITEM_TEMPLATE']) && (strlen($_REQUEST['ITEM_TEMPLATE']) > 0)){
            $extraSettings['ITEM_TEMPLATE'] = $_REQUEST['ITEM_TEMPLATE'];
        }
    }

    if (isset($_REQUEST['USE_SLIDER'])){
        if (is_string($_REQUEST['USE_SLIDER']) && (strlen($_REQUEST['USE_SLIDER']) > 0)){
            $extraSettings['USE_SLIDER'] = $_REQUEST['USE_SLIDER'];
        }
    }

    if (isset($_REQUEST['PRODUCT_NUMBER'])){
        if (is_string($_REQUEST['PRODUCT_NUMBER']) && (strlen($_REQUEST['PRODUCT_NUMBER']) > 0)){
            $extraSettings['PRODUCT_NUMBER'] = $_REQUEST['PRODUCT_NUMBER'];
        }
    }

    $arFields['EXTRA_SETTINGS'] = serialize($extraSettings);

    if(isset($_REQUEST['ID'])) {
        $arFields['TIMESTAMP_X'] = new Type\DateTime( date( 'Y-m-d H:i:s' ), 'Y-m-d H:i:s' );
        $result = CrosssellCategoryTable::update(intval($_REQUEST['ID']), $arFields);
    } else {
        $result = CrosssellCategoryTable::Add($arFields);
    }
    if($result->isSuccess()) {
        if(isset($_REQUEST['save']) && !empty($_REQUEST['save'])) {
            LocalRedirect('/bitrix/admin/kit_crosssell_list.php?lang='.SITE_ID.'&site='.$_REQUEST['site']);
        }
        else {
            $_SESSION['SAVE_CROSSSELL'][$result->getId()] = true;
            LocalRedirect($APPLICATION->GetCurPageParam("ID=".$result->getId(), array("ID")));
        }
    }
    else {
        $errors = $result->getErrorMessages();
    }
}
$arFields = array();
if($_REQUEST['ID']) {
    $arFields = CrosssellCategoryTable::getList(array(
        'filter' => array('ID' => intval($_REQUEST['ID'])),
    ))->fetch();
}else{
    $arFields = array();
    $arFields["SORT"] = 100;
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

// flash message //-----------------------------------------------------------------------------------------------------------------------------------------------
if(isset($_SESSION['SAVE_CROSSSELL'][intval($_REQUEST['ID'])])) {
    unset($_SESSION['SAVE_CROSSSELL'][intval($_REQUEST['ID'])]);
    CAdminMessage::ShowMessage(array(
        "MESSAGE" => Loc::getMessage("KIT_CROSSSELL_SUCCESS_SAVE"),
        "TYPE" => "OK"
    ));
}
else if(!empty($errors))
    CAdminMessage::ShowMessage(implode('<br>', $errors));

Loc::loadMessages(__FILE__);

// flash message //-----------------------------------------------------------------------------------------------------------------------------------------------
CJSCore::Init(array("jquery"));
?>
    <link rel="stylesheet" href="/bitrix/css/kit.opengraph/style.css">
    <script type="text/javascript" src="/bitrix/js//kit.opengraph/script.js"></script>
<?
$aTabs = array(
    array(
        "DIV" => "general_tab",
        "TAB" => Loc::getMessage("KIT_CROSSSELL_GENERAL_EDIT"),
        "ICON" => "main_user_edit",
        "TITLE" => Loc::getMessage("KIT_CROSSSELL_GENERAL_TITLE")
    ),
);

// back menu //-----------------------------------------------------------------------------------------------------------------------------------------------
if($APPLICATION->GetGroupRight('kit.crosssell') == "W")
{
    $aMenu= array(
        array(
            "TEXT" => GetMessage( "KIT_CROSSSELL_EDIT_LIST" ),
            "TITLE" => GetMessage( "KIT_CROSSSELL_EDIT_LIST_TITLE" ),
            "LINK" => '/bitrix/admin/kit_crosssell_list.php?lang='.SITE_ID.(isset($_REQUEST['site']) ? '&site='.$_REQUEST['site'] : ''),
            "ICON" => "btn_list"
        )
    );

    $aMenu[] = array(
        "TEXT" => GetMessage( "KIT_CROSSSELL_NEW_BLOCK"),
        "LINK" => "/bitrix/admin/kit_crosssell.php?lang=".SITE_ID.(isset($_REQUEST['site']) ? '&site='.$_REQUEST['site'] : '') . (isset($_REQUEST["TYPE"]) ? '&TYPE='.$_REQUEST["TYPE"] : '') . (isset($_REQUEST['CATEGORY_ID']) ? '&CATEGORY_ID='.$_REQUEST['CATEGORY_ID'].'&apply_filter=Y' : ''),
        "ICON" => "btn_new",
    );


    $aMenu[] = array(
        "TEXT" => GetMessage( "KIT_CROSSSELL_NEW_CATEGORY"),
        "LINK" => "/bitrix/admin/kit_crosssell_category.php?lang=".SITE_ID.(isset($_REQUEST['site']) ? '&site='.$_REQUEST['site'] : ''),
        "ICON" => "btn_new",
    );

    if($_REQUEST['ID'])
    {
        $urlDelete = '/bitrix/admin/kit_crosssell_list.php?lang'.SITE_ID.(isset($_REQUEST['site']) ? '&site='.$_REQUEST['site'] : '').'&sessid='.bitrix_sessid().'&type=S&delete='.$_REQUEST['ID'];

        $aMenu[] = array(
            "TEXT" => GetMessage( "KIT_CROSSSELL_DELETE_BLOCK"),
            "ONCLICK" => "javascript:if(confirm('".GetMessageJS("KIT_CROSSSELL_CATEGORY_DELETE_BLOCK_TEXT")."'))top.window.location.href='".CUtil::JSEscape($urlDelete)."'",
            "ICON" => "btn_delete",
        );
    }
}

$context = new CAdminContextMenu( $aMenu );
$context->Show();

// AutoComplete //-----------------------------------------------------------------------------------------------------------------------------------------------
$bFileman = Loader::includeModule("fileman");
$arTranslit = Array(
    'UNIQUE' => 'N',
    'TRANSLITERATION' => 'Y',
    'TRANS_LEN' => '100',
    'TRANS_CASE' => 'L',
    'TRANS_SPACE' => '_',
    'TRANS_OTHER' => '_',
    'TRANS_EAT' => 'Y',
    'USE_GOOGLE' => 'N'
);
$bLinked = (!strlen($str_TIMESTAMP_X) || $bCopy) && $_POST["linked_state"]!=='N';

CJSCore::Init(array('translit'));
?>
    <script type="text/javascript">
        var linked=<?if($bLinked) echo 'true'; else echo 'false';?>;
        dialog = new BX.CDialog({
            'content': '',
            'width':400,
            'height':400
        });
        function set_linked()
        {
            linked=!linked;

            var name_link = document.getElementById('name_link');
            if(name_link)
            {
                if(linked)
                    name_link.src='/bitrix/themes/.default/icons/iblock/link.gif';
                else
                    name_link.src='/bitrix/themes/.default/icons/iblock/unlink.gif';
            }
            var code_link = document.getElementById('code_link');
            if(code_link)
            {
                if(linked)
                    code_link.src='/bitrix/themes/.default/icons/iblock/link.gif';
                else
                    code_link.src='/bitrix/themes/.default/icons/iblock/unlink.gif';
            }
            var linked_state = document.getElementById('linked_state');
            if(linked_state)
            {
                if(linked)
                    linked_state.value='Y';
                else
                    linked_state.value='N';
            }
        }
        var oldValue = '';
        function transliterate()
        {
            if(linked)
            {
                var from = document.getElementById('NAME');
                var to = document.getElementById('SYMBOL_CODE');
                if(from && to && oldValue != from.value)
                {
                    BX.translit(from.value, {
                        'max_len' : <?echo intval($arTranslit['TRANS_LEN'])?>,
                        'change_case' : '<?echo $arTranslit['TRANS_CASE']?>',
                        'replace_space' : '<?echo $arTranslit['TRANS_SPACE']?>',
                        'replace_other' : '<?echo $arTranslit['TRANS_OTHER']?>',
                        'delete_repeat_replace' : <?echo $arTranslit['TRANS_EAT'] == 'Y'? 'true': 'false'?>,
                        'use_google' : <?echo $arTranslit['USE_GOOGLE'] == 'Y'? 'true': 'false'?>,
                        'callback' : function(result){to.value = result; setTimeout('transliterate()', 250); }
                    });
                    oldValue = from.value;
                }
                else
                {
                    setTimeout('transliterate()', 250);
                }
            }
            else
            {
                setTimeout('transliterate()', 250);
            }
        }
        transliterate();
    </script>
<?



// fields //-----------------------------------------------------------------------------------------------------------------------------------------------
$tabControl = new CAdminForm("tabControl", $aTabs);
$tabControl->Begin();
$tabControl->BeginNextFormTab();
$tabControl->AddSection("OG_COMMON_SETTINGS", GetMessage($langPrefix."SETTINGS"));
// name & symbol code //
if($arTranslit["TRANSLITERATION"] == "Y")
{
    $tabControl->BeginCustomField("NAME", GetMessage("IBLOCK_FIELD_NAME").":", true);
    $str_NAME = $arFields['NAME'];
    $str_CODE = $arFields['SYMBOL_CODE'];
    ?>
    <tr id="tr_NAME">
        <td><?echo $tabControl->GetCustomLabelHTML()?></td>
        <td style="white-space: nowrap;">
            <input type="text" size="50" name="NAME" id="NAME" maxlength="255" value="<?echo (isset($arFields['NAME'])) ? $arFields['NAME']: ((isset($_REQUEST['NAME'])) ? $_REQUEST['NAME'] : $str_NAME) ?>"><img id="name_link" title="<?echo GetMessage("IBEL_E_LINK_TIP")?>" class="linked" src="/bitrix/themes/.default/icons/iblock/<?if($bLinked) echo 'link.gif'; else echo 'unlink.gif';?>" onclick="set_linked()" />
        </td>
    </tr>
    <?
    $tabControl->EndCustomField("NAME",
        '<input type="hidden" name="NAME" id="NAME" value="'. (isset($arFields['NAME'])) ? $arFields['NAME'] : ((isset($_REQUEST['NAME'])) ? $_REQUEST['NAME'] : $str_NAME).'">'
    );

    $tabControl->BeginCustomField("SYMBOL_CODE", GetMessage("IBLOCK_FIELD_CODE").":", $arIBlock["FIELDS"]["SYMBOL_CODE"]["IS_REQUIRED"] === "Y");
    ?>
    <tr id="tr_CODE">
        <td><?echo $tabControl->GetCustomLabelHTML()?></td>
        <td style="white-space: nowrap;">
            <input type="text" size="50" name="SYMBOL_CODE" id="SYMBOL_CODE" maxlength="255" value="<?echo (isset($arFields['SYMBOL_CODE'])) ? $arFields['SYMBOL_CODE'] : $str_CODE?>"><img id="code_link" title="<?echo GetMessage("IBEL_E_LINK_TIP")?>" class="linked" src="/bitrix/themes/.default/icons/iblock/<?if($bLinked) echo 'link.gif'; else echo 'unlink.gif';?>" onclick="set_linked()" />
        </td>
    </tr>
    <?
    $tabControl->EndCustomField("SYMBOL_CODE",
        '<input type="hidden" name="SYMBOL_CODE" id="SYMBOL_CODE" value="'. (isset($arFields['SYMBOL_CODE'])) ? $arFields['SYMBOL_CODE'] : $str_CODE . '">'
    );
}
else
{
    echo "No translit";
    $tabControl->AddEditField("NAME", GetMessage($langPrefix.'CATEGORY_NAME'), false, false, $arFields['NAME']);
    $tabControl->AddEditField("SYMBOL_CODE", GetMessage($langPrefix.'SYMBOL_CODE'), false, false, $arFields['SYMBOL_CODE']);
}


$tabControl->AddEditField("SORT", GetMessage($langPrefix.'SORT'), false, false, $arFields['SORT']);

if($event->getResults()) {
    $handlerResult = $event->getResults()[0]->getType();

    if (isset($arFields['EXTRA_SETTINGS'])){
        $extraSettings = unserialize($arFields['EXTRA_SETTINGS']);
    }

    $itemTemplate = array();
    if (is_array($handlerResult['template_field'])){
        foreach ($handlerResult['template_field'] as $templateName){
            $itemTemplate['REFERENCE'][$templateName] = $templateName;
            $itemTemplate['REFERENCE_ID'][$templateName] = $templateName;
        }
        $tabControl->BeginCustomField( "ITEM_TEMPLATE", Loc::getMessage('KIT_CROSSSELL_TAB_1_TEMPLATE'), false );
        ?>
        <tr id="ITEM_TEMPLATE">
            <td width="40%"><? echo $tabControl->GetCustomLabelHTML(); ?></td>
            <td width="60%">
                <?echo SelectBoxFromArray('ITEM_TEMPLATE', $itemTemplate, (isset($extraSettings['ITEM_TEMPLATE'])) ? $extraSettings['ITEM_TEMPLATE']:'' ,'',"class='template-select'",'','style="min-width:350px"');?>
            </td>
        </tr>
        <?
        $tabControl->EndCustomField( "ITEM_TEMPLATE" );
    }

    $itemSlider = array();
    if (is_array($handlerResult['show_slider_field'])){
        foreach ($handlerResult['show_slider_field'] as $showSlider){
            $itemSlider['REFERENCE'][$showSlider] = $showSlider;
            $itemSlider['REFERENCE_ID'][$showSlider] = $showSlider;
        }
        $tabControl->BeginCustomField( "USE_SLIDER", Loc::getMessage('KIT_CROSSSELL_TAB_1_SLIDER'), false );
        ?>
        <tr id="USE_SLIDER">
            <td width="40%"><? echo $tabControl->GetCustomLabelHTML(); ?></td>
            <td width="60%">
                <?echo SelectBoxFromArray('USE_SLIDER', $itemSlider, (isset($extraSettings['USE_SLIDER'])) ? $extraSettings['USE_SLIDER']:'','',"class='slider-select'",'','style="min-width:350px"');?>
            </td>
        </tr>
        <?
        $tabControl->EndCustomField( "USE_SLIDER" );
    }

    $tabControl->AddEditField("PRODUCT_NUMBER", Loc::GetMessage('KIT_CROSSSELL_TAB_1_PRODUCT_NUMBER'), false, false, (isset($extraSettings['PRODUCT_NUMBER'])) ? $extraSettings['PRODUCT_NUMBER']:'10');
}

if($APPLICATION->GetGroupRight('kit.crosssell') == "W")
    $tabControl->Buttons($arButtonsParams);
$tabControl->arParams["FORM_ACTION"] = $APPLICATION->GetCurPageParam();
$tabControl->Show();
$tabControl->ShowWarnings($tabControl->GetName(), $message);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>