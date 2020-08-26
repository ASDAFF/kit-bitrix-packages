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

if(preg_match("/\[itemListElement\]\[\d*\]\[/iu", $key) == 0)
    $key = KitSchema::checkKey($key);

$aTabs = array(
    array(
        "DIV" => $_POST['entity'],
        "ICON" => "main_user_edit",
    ),);
$langPrefix = 'KIT_SCHEMA_LISTITEMS_';
$tabControl = new CAdminForm("tabControl", $aTabs);
$tabControl->AddSection("OG_COMMON_SETTINGS", GetMessage($langPrefix."SETTINGS"));
$tabControl->BeginCustomField("type_BLOCK", '', false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <input type="hidden" name="<?=$key ? $key."[@type]" : "@type"?>" value="ListItems"/>
        </td>
    </tr>
<?
$tabControl->EndCustomField("type_BLOCK");
$tabControl->BeginCustomField("remove-this_BLOCK", '', false);
?>
    <tr class="adm-detail-file-row tr-btn-remove-this-entity">
        <td colspan="2" style="text-align: left;">
            <input type="button" value="<?=GetMessage($langPrefix."MINUS");?>" class="remove-this-entity"/>
        </td>
    </tr>
<?
$tabControl->EndCustomField("remove-this_BLOCK");
$tabControl->AddEditField($key ? $key."[position]" : "position", GetMessage($langPrefix.'POSITION'), true, false,
    ( isset($arFields["position"]) & !empty($arFields["position"]) ? $arFields["position"] : "" ));

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "previousItem",
    array(
        '' => '...',
        'listitem' => 'ListItem',
    ),
    $key, $langPrefix, $arFields, false);
$tabControl = KitSchema::makeSingleDropDownField($tabControl, "nextItem",
    array(
        '' => '...',
        'listitem' => 'ListItem',
    ),
    $key, $langPrefix, $arFields, false);

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "item",
    array(
        '' => '...',
        'thing' => 'Thing',
    ),
    $key, $langPrefix, $arFields, false);

require (dirname(__FILE__).'/blocks/thing.php');
$tabControl->Show();
?>