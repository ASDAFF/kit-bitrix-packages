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
$langPrefix = 'KIT_SCHEMA_MEDIAOBJECT_';
$tabControl = new CAdminForm("tabControl", $aTabs);
$tabControl->AddSection("OG_COMMON_SETTINGS", GetMessage($langPrefix."SETTINGS"));
$tabControl->BeginCustomField("type_BLOCK", '', false);
?>
    <tr class="adm-detail-file-row">
        <td colspan="2" style="text-align: center">
            <input type="hidden" name="<?=$key ? $key."[@type]" : "@type"?>" value="MediaObject"/>
        </td>
    </tr>
<?
$tabControl->EndCustomField("type_BLOCK");
$tabControl = KitSchema::makeSingleDropDownField($tabControl, "associatedArticle",
    array(
        '' => '...',
        'newsarticle' => 'NewsArticle',
    ),
    $key, $langPrefix, $arFields);

$tabControl->AddEditField($key ? $key."[bitrate]" : "bitrate", GetMessage($langPrefix.'bitrate'), false, false,
    ( isset($arFields["bitrate"]) & !empty($arFields["bitrate"]) ? $arFields["bitrate"] : "" ));
$tabControl->AddEditField($key ? $key."[contentSize]" : "contentSize", GetMessage($langPrefix.'contentSize'), false, false,
    ( isset($arFields["contentSize"]) & !empty($arFields["contentSize"]) ? $arFields["contentSize"] : "" ));
$tabControl->AddEditField($key ? $key."[contentUrl]" : "contentUrl", GetMessage($langPrefix.'contentUrl'), false, false,
    ( isset($arFields["contentUrl"]) & !empty($arFields["contentUrl"]) ? $arFields["contentUrl"] : "" ));
$tabControl->AddEditField($key ? $key."[duration]" : "duration", GetMessage($langPrefix.'duration'), false, false,
    ( isset($arFields["duration"]) & !empty($arFields["duration"]) ? $arFields["duration"] : "" ));
$tabControl->AddEditField($key ? $key."[embedUrl]" : "embedUrl", GetMessage($langPrefix.'embedUrl'), false, false,
    ( isset($arFields["embedUrl"]) & !empty($arFields["embedUrl"]) ? $arFields["embedUrl"] : "" ));

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "encodesCreativeWork",
    array(
        '' => '...',
        'creativework ' => 'CreativeWork',
    ),
    $key, $langPrefix, $arFields);

$tabControl->AddEditField($key ? $key."[encodingFormat]" : "encodingFormat", GetMessage($langPrefix.'encodingFormat'), false, false,
    ( isset($arFields["encodingFormat"]) & !empty($arFields["encodingFormat"]) ? $arFields["encodingFormat"] : "" ));
$tabControl->AddEditField($key ? $key."[startTime]" : "startTime", GetMessage($langPrefix.'startTime'), false, false,
    ( isset($arFields["startTime"]) & !empty($arFields["startTime"]) ? $arFields["startTime"] : "" ));
$tabControl->AddEditField($key ? $key."[endTime]" : "endTime", GetMessage($langPrefix.'endTime'), false, false,
    ( isset($arFields["endTime"]) & !empty($arFields["endTime"]) ? $arFields["endTime"] : "" ));
$tabControl->AddEditField($key ? $key."[height]" : "height", GetMessage($langPrefix.'height'), false, false,
    ( isset($arFields["height"]) & !empty($arFields["height"]) ? $arFields["height"] : "" ));
$tabControl->AddEditField($key ? $key."[width]" : "width", GetMessage($langPrefix.'width'), false, false,
    ( isset($arFields["width"]) & !empty($arFields["width"]) ? $arFields["width"] : "" ));
$tabControl->AddEditField($key ? $key."[playerType]" : "playerType", GetMessage($langPrefix.'playerType'), false, false,
    ( isset($arFields["playerType"]) & !empty($arFields["playerType"]) ? $arFields["playerType"] : "" ));

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "productionCompany",
    array(
        '' => '...',
        'organization  ' => 'Organization',
    ),
    $key, $langPrefix, $arFields);

$tabControl = KitSchema::makeSingleDropDownField($tabControl, "regionsAllowed",
    array(
        '' => '...',
        'place' => 'Place',
    ),
    $key, $langPrefix, $arFields);

$tabControl->AddEditField($key ? $key."[requiresSubscription]" : "requiresSubscription", GetMessage($langPrefix.'requiresSubscription'), false, false,
    ( isset($arFields["requiresSubscription"]) & !empty($arFields["requiresSubscription"]) ? $arFields["requiresSubscription"] : "" ));
$tabControl->AddEditField($key ? $key."[uploadDate]" : "uploadDate", GetMessage($langPrefix.'uploadDate'), false, false,
    ( isset($arFields["uploadDate"]) & !empty($arFields["uploadDate"]) ? $arFields["uploadDate"] : "" ));

require (dirname(__FILE__).'/blocks/thing.php');
$tabControl->Show();
?>