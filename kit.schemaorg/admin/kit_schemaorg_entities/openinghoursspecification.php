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
$langPrefix = 'KIT_SCHEMA_OPENINGHOURSSPECIFICATION_';
$tabControl = new CAdminForm("tabControl", $aTabs);
$tabControl->AddSection("OG_COMMON_SETTINGS", GetMessage($langPrefix."SETTINGS"));
$tabControl->BeginCustomField("type_BLOCK", '', false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <input type="hidden" name="<?=$key ? $key."[@type]" : "@type"?>" value="OpeningHoursSpecification"/>
        </td>
    </tr>
<?
$tabControl->EndCustomField("type_BLOCK");
$tabControl->AddEditField($key ? $key."[closes]" : "closes", GetMessage($langPrefix.'closes'), false, false,
    ( isset($arFields["closes"]) & !empty($arFields["closes"]) ? $arFields["closes"] : "" ));
$tabControl->AddEditField($key ? $key."[dayOfWeek]" : "dayOfWeek", GetMessage($langPrefix.'dayOfWeek'), false, false,
    ( isset($arFields["dayOfWeek"]) & !empty($arFields["dayOfWeek"]) ? $arFields["dayOfWeek"] : "" ));
$tabControl->AddEditField($key ? $key."[opens]" : "opens", GetMessage($langPrefix.'opens'), false, false,
    ( isset($arFields["opens"]) & !empty($arFields["opens"]) ? $arFields["opens"] : "" ));
$tabControl->AddEditField($key ? $key."[validFrom]" : "validFrom", GetMessage($langPrefix.'validFrom'), false, false,
    ( isset($arFields["validFrom"]) & !empty($arFields["validFrom"]) ? $arFields["validFrom"] : "" ));
$tabControl->AddEditField($key ? $key."[validThrough]" : "validThrough", GetMessage($langPrefix.'validThrough'), false, false,
    ( isset($arFields["validThrough"]) & !empty($arFields["validThrough"]) ? $arFields["validThrough"] : "" ));

require (dirname(__FILE__).'/blocks/thing.php');
$tabControl->Show();
?>