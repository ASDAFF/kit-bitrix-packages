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
$langPrefix = 'SOTBIT_SCHEMA_NEWSARTICLE_';
$tabControl = new CAdminForm("tabControl", $aTabs);
$tabControl->AddSection("OG_COMMON_SETTINGS", GetMessage($langPrefix."SETTINGS"));
$tabControl->BeginCustomField("type_BLOCK", '', false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <input type="hidden" name="<?=$key ? $key."[@type]" : "@type"?>" value="NewsArticle"/>
        </td>
    </tr>
<?
$tabControl->EndCustomField("type_BLOCK");
$tabControl->AddEditField($key ? $key."[dateline]" : "dateline", GetMessage($langPrefix.'dateline'), false, false,
    ( isset($arFields["dateline"]) & !empty($arFields["dateline"]) ? $arFields["dateline"] : "" ));
$tabControl->AddEditField($key ? $key."[printColumn]" : "printColumn", GetMessage($langPrefix.'printColumn'), false, false,
    ( isset($arFields["printColumn"]) & !empty($arFields["printColumn"]) ? $arFields["printColumn"] : "" ));
$tabControl->AddEditField($key ? $key."[printEdition]" : "printEdition", GetMessage($langPrefix.'printEdition'), false, false,
    ( isset($arFields["printEdition"]) & !empty($arFields["printEdition"]) ? $arFields["printEdition"] : "" ));
$tabControl->AddEditField($key ? $key."[printPage]" : "printPage", GetMessage($langPrefix.'printPage'), false, false,
    ( isset($arFields["printPage"]) & !empty($arFields["printPage"]) ? $arFields["printPage"] : "" ));
$tabControl->AddEditField($key ? $key."[printSection]" : "printSection", GetMessage($langPrefix.'printSection'), false, false,
    ( isset($arFields["printSection"]) & !empty($arFields["printSection"]) ? $arFields["printSection"] : "" ));

require (dirname(__FILE__).'/blocks/article.php');
require (dirname(__FILE__).'/blocks/thing.php');
$tabControl->Show();
?>