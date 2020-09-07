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
$langPrefix = 'KIT_SCHEMA_QUESTION_';
$tabControl = new CAdminForm("tabControl", $aTabs);

$tabControl->AddSection("OG_COMMON_SETTINGS", GetMessage($langPrefix."SETTINGS"));
$tabControl->BeginCustomField("type_BLOCK", '', false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <input type="hidden" name="<?=$key ? $key."[@type]" : "@type"?>" value="Question"/>
        </td>
    </tr>
<?
$tabControl->EndCustomField("type_BLOCK");
$tabControl->BeginCustomField("remove-this_BLOCK", '', false);
?>
    <tr class="adm-detail-file-row tr-btn-remove-this-entity">
        <td colspan="2" style="text-align: left">
            <input type="button" value="<?=GetMessage($langPrefix."MINUS");?>" class="remove-this-entity"/>
        </td>
    </tr>
<?
$tabControl->EndCustomField("remove-this_BLOCK");

$tabControl = KitSchema::makeMultipleDropDownField($tabControl, "acceptedAnswer",
    array(
        '' => '...',
        'answer' => 'Answer',
    ),
    $key, $langPrefix, $arFields);

$tabControl->AddEditField($key ? $key."[answerCount]" : "answerCount", Loc::getMessage($langPrefix.'answerCount'), false, false,
    ( isset($arFields["answerCount"]) & !empty($arFields["answerCount"]) ? $arFields["answerCount"] : "" ));
$tabControl->AddEditField($key ? $key."[downvoteCount]" : "downvoteCount", Loc::getMessage($langPrefix.'downvoteCount'), false, false,
    ( isset($arFields["downvoteCount"]) & !empty($arFields["downvoteCount"]) ? $arFields["downvoteCount"] : "" ));

require(dirname(__FILE__) . '/blocks/creativework.php');
require(dirname(__FILE__) . '/blocks/thing.php');
$tabControl->Show();
?>