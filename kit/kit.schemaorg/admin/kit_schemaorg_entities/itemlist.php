<?
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
//-----------------------------------------------------------------------------------------------------------------------------------------------
if(isset($_POST['key']) || isset($_POST['entity']))
    $key = $_POST['key'] == 'ENTITIES' ? $_POST['entity'] : $_POST['key'];
if(!isset($key) || empty($key)) {
    if(isset($arFields["ENTITIES"]) && !empty($arFields["ENTITIES"]))
    {
        $key = strtolower($arFields["ENTITIES"]["@type"]);
        $arFields = $arFields["ENTITIES"];
    }
    else
        $key = strtolower($arFields["@type"]);
}

$aTabs = array(
    array(
        "DIV" => $_POST['entity'],
        "ICON" => "main_user_edit",
    ),);
$langPrefix = 'KIT_SCHEMA_ITEMLIST_';
$tabControl = new CAdminForm("tabControl", $aTabs);
$tabControl->AddSection("OG_COMMON_SETTINGS", GetMessage($langPrefix."SETTINGS"));
$tabControl->BeginCustomField("type_BLOCK", '', false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <input type="hidden" name="<?=$key ? $key."[@type]" : "@type"?>" value="ItemList"/>
        </td>
    </tr>
<?
$tabControl->EndCustomField("type_BLOCK");
$tabControl = KitSchema::makeMultipleDropDownField($tabControl, "itemListElement",
    array(
        '' => '...',
        'listitems' => 'ListItems',
    ),
    $key, $langPrefix, $arFields);

//$tabControl = KitSchema::makeMultipleDropDownField($tabControl, "itemListElement",
//    array(
//        '' => '...',
//        'listitem' => 'ListItem',
//    ),
//    $key, $langPrefix, $arFields);

$tabControl->AddEditField($key ? $key."[itemListOrder]" : "itemListOrder", GetMessage($langPrefix.'itemListOrder'), false, false,
    ( isset($arFields["itemListOrder"]) & !empty($arFields["itemListOrder"]) ? $arFields["itemListOrder"] : "" ));

$tabControl->AddEditField($key ? $key."[numberOfItems]" : "numberOfItems", GetMessage($langPrefix.'numberOfItems'), false, false,
    ( isset($arFields["numberOfItems"]) & !empty($arFields["numberOfItems"]) ? $arFields["numberOfItems"] : "" ));


require (dirname(__FILE__).'/blocks/thing.php');
$tabControl->Show();
?>