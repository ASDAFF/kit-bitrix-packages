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
$langPrefix = 'SOTBIT_SCHEMA_VIDEOOBJECT_';
$tabControl = new CAdminForm("tabControl", $aTabs);
$tabControl->AddSection("OG_COMMON_SETTINGS", GetMessage($langPrefix."SETTINGS"));
$tabControl->BeginCustomField("type_BLOCK", '', false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <input type="hidden" name="<?=$key ? $key."[@type]" : "@type"?>" value="VideoObject"/>
        </td>
    </tr>
<?
$tabControl->EndCustomField("type_BLOCK");
$tabControl = SotbitSchema::makeSingleDropDownField($tabControl, "actor",
    array(
        '' => '...',
        'person' => 'Person',
    ),
    $key, $langPrefix, $arFields);

$tabControl->AddEditField($key ? $key."[caption]" : "caption", GetMessage($langPrefix.'caption'), false, false,
    ( isset($arFields["caption"]) & !empty($arFields["caption"]) ? $arFields["caption"] : "" ));

$tabControl = SotbitSchema::makeSingleDropDownField($tabControl, "director",
    array(
        '' => '...',
        'person' => 'Person',
    ),
    $key, $langPrefix, $arFields);

$tabControl = SotbitSchema::makeSingleDropDownField($tabControl, "musicBy",
    array(
        '' => '...',
        'person' => 'Person',
    ),
    $key, $langPrefix, $arFields);

$tabControl = SotbitSchema::makeSingleDropDownField($tabControl, "thumbnail",
    array(
        '' => '...',
        'imageobject' => 'ImageObject',
    ),
    $key, $langPrefix, $arFields);

$tabControl->AddEditField($key ? $key."[transcript]" : "transcript", GetMessage($langPrefix.'transcript'), false, false,
    ( isset($arFields["transcript"]) & !empty($arFields["transcript"]) ? $arFields["transcript"] : "" ));
$tabControl->AddEditField($key ? $key."[videoFrameSize]" : "videoFrameSize", GetMessage($langPrefix.'videoFrameSize'), false, false,
    ( isset($arFields["videoFrameSize"]) & !empty($arFields["videoFrameSize"]) ? $arFields["videoFrameSize"] : "" ));
$tabControl->AddEditField($key ? $key."[videoQuality]" : "videoQuality", GetMessage($langPrefix.'videoQuality'), false, false,
    ( isset($arFields["videoQuality"]) & !empty($arFields["videoQuality"]) ? $arFields["videoQuality"] : "" ));

require (dirname(__FILE__).'/blocks/mediaobject.php');
require (dirname(__FILE__).'/blocks/creativework.php');
require (dirname(__FILE__).'/blocks/thing.php');
$tabControl->Show();
?>