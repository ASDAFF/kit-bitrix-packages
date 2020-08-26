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
$langPrefix = 'KIT_SCHEMA_PROPERTYVALUESPECIFICATION_';
$tabControl = new CAdminForm("tabControl", $aTabs);
$tabControl->AddSection("OG_COMMON_SETTINGS", GetMessage($langPrefix."SETTINGS"));
$tabControl->BeginCustomField("type_BLOCK", '', false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <input type="hidden" name="<?=$key ? $key."[@type]" : "@type"?>" value="PropertyValueSpecification"/>
        </td>
    </tr>
<?
$tabControl->EndCustomField("type_BLOCK");
$tabControl->AddEditField($key ? $key."[valueName]" : "valueName", GetMessage($langPrefix.'valueName'), true, false,
    ( isset($arFields["valueName"]) & !empty($arFields["valueName"]) ? $arFields["valueName"] : "" ));
$tabControl->AddEditField($key ? $key."[valuePattern]" : "valuePattern", GetMessage($langPrefix.'valuePattern'), false, false,
    ( isset($arFields["valuePattern"]) & !empty($arFields["valuePattern"]) ? $arFields["valuePattern"] : "" ));
$tabControl->AddEditField($key ? $key."[valueRequired]" : "valueRequired", GetMessage($langPrefix.'valueRequired'), false, false,
    ( isset($arFields["valueRequired"]) & !empty($arFields["valueRequired"]) ? $arFields["valueRequired"] : "" ));
$tabControl->AddEditField($key ? $key."[defaultValue]" : "defaultValue", GetMessage($langPrefix.'defaultValue'), false, false,
    ( isset($arFields["defaultValue"]) & !empty($arFields["defaultValue"]) ? $arFields["defaultValue"] : "" ));
$tabControl->AddEditField($key ? $key."[maxValue]" : "maxValue", GetMessage($langPrefix.'maxValue'), false, false,
    ( isset($arFields["maxValue"]) & !empty($arFields["maxValue"]) ? $arFields["maxValue"] : "" ));
$tabControl->AddEditField($key ? $key."[minValue]" : "minValue", GetMessage($langPrefix.'minValue'), false, false,
    ( isset($arFields["minValue"]) & !empty($arFields["minValue"]) ? $arFields["minValue"] : "" ));
$tabControl->AddEditField($key ? $key."[multipleValues]" : "multipleValues", GetMessage($langPrefix.'multipleValues'), false, false,
    ( isset($arFields["multipleValues"]) & !empty($arFields["multipleValues"]) ? $arFields["multipleValues"] : "" ));
$tabControl->AddEditField($key ? $key."[readonlyValue]" : "readonlyValue", GetMessage($langPrefix.'readonlyValue'), false, false,
    ( isset($arFields["readonlyValue"]) & !empty($arFields["readonlyValue"]) ? $arFields["readonlyValue"] : "" ));
$tabControl->AddEditField($key ? $key."[valueMaxLength]" : "valueMaxLength", GetMessage($langPrefix.'valueMaxLength'), false, false,
    ( isset($arFields["valueMaxLength"]) & !empty($arFields["valueMaxLength"]) ? $arFields["valueMaxLength"] : "" ));
$tabControl->AddEditField($key ? $key."[valueMinLength]" : "valueMinLength", GetMessage($langPrefix.'valueMinLength'), false, false,
    ( isset($arFields["valueMinLength"]) & !empty($arFields["valueMinLength"]) ? $arFields["valueMinLength"] : "" ));


require (dirname(__FILE__).'/blocks/thing.php');
$tabControl->Show();
?>