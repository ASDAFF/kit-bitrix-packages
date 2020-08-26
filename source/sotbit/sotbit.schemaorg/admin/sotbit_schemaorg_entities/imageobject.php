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
$langPrefix = 'SOTBIT_SCHEMA_IMAGEOBJECT_';
$tabControl = new CAdminForm("tabControl", $aTabs);

$tabControl->AddSection($langPrefix . "SETTINGS", GetMessage($langPrefix."SETTINGS"));
$tabControl->BeginCustomField("type_BLOCK", '', false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <input type="hidden" name="<?=$key ? $key."[@type]" : "@type"?>" value="ImageObject"/>
        </td>
    </tr>
<?
$tabControl->EndCustomField("type_BLOCK");


$tabControl->AddEditField($key ? $key."[caption]" : "caption", GetMessage($langPrefix.'caption'), false, false,
    ( isset($arFields["caption"]) & !empty($arFields["caption"]) ? $arFields["caption"] : "" ));
$tabControl->AddEditField($key ? $key."[exifData]" : "exifData", GetMessage($langPrefix.'exifData'), false, false,
    ( isset($arFields["exifData"]) & !empty($arFields["exifData"]) ? $arFields["exifData"] : "" ));

require (dirname(__FILE__).'/blocks/thing.php');
$tabControl->Show();
?>