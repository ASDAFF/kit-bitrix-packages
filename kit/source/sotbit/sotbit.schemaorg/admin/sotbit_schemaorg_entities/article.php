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

$langPrefix = 'SOTBIT_SCHEMA_ARTICLE_';

$tabControl = new CAdminForm("tabControl", $aTabs);
$tabControl->AddSection("OG_COMMON_SETTINGS", GetMessage($langPrefix."SETTINGS"));
$tabControl->BeginCustomField("type_BLOCK", '', false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <input type="hidden" name="<?=$key ? $key."[@type]" : "@type"?>" value="Article"/>
        </td>
    </tr>
<?
$tabControl->EndCustomField("type_BLOCK");

$tabControl->AddEditField($key ? $key."[articleBody]" : "articleBody", GetMessage($langPrefix.'articleBody'), false, false,
    ( isset($arFields["articleBody"]) & !empty($arFields["articleBody"]) ? $arFields["articleBody"] : "" ));
$tabControl->AddEditField($key ? $key."[articleSection]" : "articleSection", GetMessage($langPrefix.'articleSection'), false, false,
    ( isset($arFields["articleSection"]) & !empty($arFields["articleSection"]) ? $arFields["articleSection"] : "" ));
$tabControl->AddEditField($key ? $key."[backstory]" : "backstory", GetMessage($langPrefix.'backstory'), false, false,
    ( isset($arFields["backstory"]) & !empty($arFields["backstory"]) ? $arFields["backstory"] : "" ));
$tabControl->AddEditField($key ? $key."[wordCount]" : "wordCount", GetMessage($langPrefix.'wordCount'), false, false,
    ( isset($arFields["wordCount"]) & !empty($arFields["wordCount"]) ? $arFields["wordCount"] : "" ));

require (dirname(__FILE__).'/blocks/creativework.php');
require (dirname(__FILE__).'/blocks/thing.php');
$tabControl->Show();
?>